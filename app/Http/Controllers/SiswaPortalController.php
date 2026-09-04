<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\PengumpulanTugas;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class SiswaPortalController extends Controller
{
    /** Dapatkan data profil Siswa dari User yang sedang login */
    private function getSiswaProfile()
    {
        $user = Auth::user();
        if (!$user) return null;

        $siswa = Siswa::where('user_id', $user->id_user)
            ->orWhere('email', $user->email)
            ->first();

        return $siswa;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Jika yang membuka adalah Admin, arahkan langsung ke Dashboard Admin
        if ($user && $user->role?->name !== 'Siswa') {
            return redirect()->route('dashboard');
        }

        $siswa = $this->getSiswaProfile();

        if (!$siswa) {
            return view('siswa.no-profile');
        }

        // Kehadiran %
        $totalPresensi = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->count();
        $hadirCount = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'hadir')->count();
        $persenKehadiran = $totalPresensi > 0 ? round(($hadirCount / $totalPresensi) * 100, 1) : 100.0;

        // Tugas Aktif (Deadline >= Sekarang & belum mengumpulkan atau baru mengumpulkan)
        $tugasList = Tugas::where('kelas', $siswa->kelas)
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();

        $tugasKumpulIds = PengumpulanTugas::where('siswa_id', $siswa->id_siswa)
            ->pluck('tugas_id')
            ->toArray();

        // Ujian Online Aktif
        $exams = Exam::where('aktif', true)->take(5)->get();

        // Kalkulasi Rangking Siswa di Kelasnya (Terhubung 100% dengan Rekap Admin)
        $adminController = new SiswaAdminController();
        $rankedData = $adminController->getRankedRekapData($siswa->kelas);
        $temanSekelas = Siswa::where('kelas', $siswa->kelas)->get();

        $myRecord = $rankedData->firstWhere('siswa.id_siswa', $siswa->id_siswa);
        $rankingPos = $myRecord ? $myRecord['ranking'] : '-';

        return view('siswa.dashboard', compact('siswa', 'persenKehadiran', 'tugasList', 'tugasKumpulIds', 'exams', 'rankingPos', 'temanSekelas'));
    }

    public function presensi()
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return redirect()->route('siswa.dashboard');

        $presensiList = PresensiSiswa::where('siswa_id', $siswa->id_siswa)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $statHadir = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'hadir')->count();
        $statSakit = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'sakit')->count();
        $statIzin = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'izin')->count();
        $statAlpa = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'alpa')->count();

        return view('siswa.presensi', compact('siswa', 'presensiList', 'statHadir', 'statSakit', 'statIzin', 'statAlpa'));
    }

    public function tugas()
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return redirect()->route('siswa.dashboard');

        $tugasList = Tugas::where('kelas', $siswa->kelas)
            ->orderBy('deadline', 'desc')
            ->paginate(10);

        $submittedTugas = PengumpulanTugas::where('siswa_id', $siswa->id_siswa)
            ->get()
            ->keyBy('tugas_id');

        return view('siswa.tugas', compact('siswa', 'tugasList', 'submittedTugas'));
    }

    public function storeTugas(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return back()->with('error', 'Profil siswa tidak ditemukan.');

        $request->validate([
            'jawaban_teks' => ['nullable', 'string'],
            'file_tugas' => ['nullable', 'file', 'max:10240'],
        ]);

        if (!$request->jawaban_teks && !$request->hasFile('file_tugas')) {
            return back()->with('error', 'Wajib memasukkan jawaban teks atau melampirkan berkas tugas.');
        }

        $existing = PengumpulanTugas::where('tugas_id', $tugas->id_tugas)
            ->where('siswa_id', $siswa->id_siswa)
            ->first();

        $filePath = $existing?->file_tugas;
        if ($request->hasFile('file_tugas')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_tugas')->store('jawaban_tugas', 'public');
        }

        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id_tugas,
                'siswa_id' => $siswa->id_siswa,
            ],
            [
                'file_tugas' => $filePath,
                'jawaban_teks' => $request->jawaban_teks,
                'tanggal_kumpul' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function nilai()
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return redirect()->route('siswa.dashboard');

        // Gunakan kalkulasi rekap & perankingan terpadu dari SiswaAdminController
        $adminController = new SiswaAdminController();
        $rankedData = $adminController->getRankedRekapData($siswa->kelas);

        $myRekap = $rankedData->firstWhere('siswa.id_siswa', $siswa->id_siswa);

        $avgTugas = $myRekap ? $myRekap['nilai_tugas'] : 0.0;
        $nilaiUH = $myRekap ? $myRekap['nilai_uh'] : 0.0;
        $nilaiUTS = $myRekap ? $myRekap['nilai_uts'] : 0.0;
        $nilaiUAS = $myRekap ? $myRekap['nilai_uas'] : 0.0;
        $nilaiAkhir = $myRekap ? $myRekap['nilai_akhir'] : 0.0;
        $rankingPos = $myRekap ? $myRekap['ranking'] : '-';
        $totalSiswaKelas = $rankedData->count();

        // Detail Nilai Tugas
        $pengumpulanTugas = PengumpulanTugas::with('tugas')
            ->where('siswa_id', $siswa->id_siswa)
            ->get();

        // Detail Nilai Ujian Online
        $user = Auth::user();
        $identifiers = array_filter([$siswa->nis, $siswa->email, $user?->username]);
        $examAttempts = ExamAttempt::with('exam')
            ->whereIn('nis_email', $identifiers)
            ->where('status', 'selesai')
            ->get();

        return view('siswa.nilai', compact('siswa', 'pengumpulanTugas', 'examAttempts', 'avgTugas', 'nilaiUH', 'nilaiUTS', 'nilaiUAS', 'nilaiAkhir', 'rankingPos', 'totalSiswaKelas'));
    }

    public function printDetailNilaiSaya()
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return redirect()->route('siswa.dashboard');

        $adminController = new SiswaAdminController();
        return $adminController->printDetailNilaiSiswa($siswa);
    }

    public function showChangePasswordForm()
    {
        $siswa = $this->getSiswaProfile();
        if (!$siswa) return redirect()->route('siswa.dashboard');

        return view('siswa.password', compact('siswa'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Wajib memasukkan password lama Anda.',
            'password.required'         => 'Wajib memasukkan password baru.',
            'password.min'              => 'Password baru minimal harus 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('siswa.dashboard');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan tidak sesuai.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record('update', 'Siswa memperbarui password akun mandiri', $user);
        }

        return back()->with('success', 'Password akun Anda berhasil diperbarui! Silakan gunakan password baru ini untuk login berikutnya.');
    }
}
