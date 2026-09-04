<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ExamAttempt;
use App\Models\PengumpulanTugas;
use App\Models\PresensiSiswa;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaAdminController extends Controller
{
    // ==========================================
    // 1. KELOLA DATA SISWA (CRUD & WHITELIST)
    // ==========================================
    public function indexSiswa(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $siswa = $query->orderBy('kelas')->orderBy('nama_lengkap')->paginate(15);
        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');

        return view('admin.siswa.index', compact('siswa', 'kelases'));
    }

    public function createSiswa()
    {
        return view('admin.siswa.create');
    }

    public function storeSiswa(Request $request)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswa,nis'],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:siswa,nisn'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas' => ['required', 'string', 'max:100'],
            'tahun_masuk' => ['required', 'string', 'max:10'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        $validated['status'] = 'pending_register';

        $siswa = Siswa::create($validated);

        ActivityLog::record('create_siswa', "Admin menambahkan data pra-registrasi siswa: {$siswa->nama_lengkap} (NIS: {$siswa->nis}, Email: {$siswa->email})", Auth::user(), Auth::id());

        return redirect()->route('admin.siswa.index')->with('success', "Data siswa {$siswa->nama_lengkap} berhasil dibuat. Email ({$siswa->email}) dan NIS ({$siswa->nis}) telah di-whitelist untuk registrasi.");
    }

    public function editSiswa(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function updateSiswa(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa'],
            'nisn' => ['nullable', 'string', 'max:50', 'unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email,' . $siswa->id_siswa . ',id_siswa'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas' => ['required', 'string', 'max:100'],
            'tahun_masuk' => ['required', 'string', 'max:10'],
            'status' => ['required', 'in:pending_register,aktif,alumni,non_aktif'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        $siswa->update($validated);

        if ($siswa->user) {
            $siswa->user->update([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
            ]);
        }

        ActivityLog::record('update_siswa', "Admin mengutarakan perubahan data siswa: {$siswa->nama_lengkap}", Auth::user(), Auth::id());

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function deleteSiswa(Siswa $siswa)
    {
        $nama = $siswa->nama_lengkap;
        if ($siswa->user) {
            $siswa->user->delete();
        }
        $siswa->delete();

        ActivityLog::record('delete_siswa', "Admin menghapus siswa: {$nama}", Auth::user(), Auth::id());

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function resetPasswordSiswa(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $user = $siswa->user ?? User::where('email', $siswa->email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            if (!$siswa->user_id) {
                $siswa->update([
                    'user_id' => $user->id_user,
                    'status' => 'aktif',
                ]);
            }
        } else {
            $siswaRole = Role::where('name', 'Siswa')->first();
            if (!$siswaRole) {
                $siswaRole = Role::create([
                    'name' => 'Siswa',
                    'description' => 'Akses portal siswa, presensi, tugas, dan ujian online',
                ]);
            }

            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', strtok($siswa->email, '@')));
            if (empty($baseUsername) || User::where('username', $baseUsername)->exists()) {
                $baseUsername = 'siswa_' . $siswa->nis;
            }

            $user = User::create([
                'role_id' => $siswaRole->id_role,
                'name' => $siswa->nama_lengkap,
                'username' => $baseUsername,
                'email' => $siswa->email,
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            $siswa->update([
                'user_id' => $user->id_user,
                'status' => 'aktif',
            ]);
        }

        ActivityLog::record('reset_password_siswa', "Admin mereset/mengubah password akun siswa: {$siswa->nama_lengkap} (Email: {$siswa->email})", Auth::user(), Auth::id());

        return back()->with('success', "Password akun siswa {$siswa->nama_lengkap} (Username: {$user->username}) berhasil diperbarui.");
    }

    // ==========================================
    // 2. KELOLA PRESENSI SISWA
    // ==========================================
    public function indexPresensi(Request $request)
    {
        $selectedKelas = $request->get('kelas', 'X MIPA 1');
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');
        if ($kelases->isEmpty()) {
            $kelases = collect(['X MIPA 1', 'XI MIPA 1', 'XII MIPA 1']);
        }

        $daftarSiswa = Siswa::where('kelas', $selectedKelas)
            ->orderBy('nama_lengkap')
            ->get();

        $existingPresensi = PresensiSiswa::whereIn('siswa_id', $daftarSiswa->pluck('id_siswa'))
            ->whereDate('tanggal', $selectedTanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('admin.presensi.index', compact('selectedKelas', 'selectedTanggal', 'kelases', 'daftarSiswa', 'existingPresensi'));
    }

    public function printPresensiBulanan(Request $request)
    {
        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');
        if ($kelases->isEmpty()) {
            $kelases = collect(['X MIPA 1']);
        }

        $selectedKelas = $request->get('kelas', $kelases->first());
        $selectedBulan = (int) $request->get('bulan', date('n'));
        $selectedTahun = (int) $request->get('tahun', date('Y'));

        $daysInMonth = \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->daysInMonth;

        $daftarSiswa = Siswa::where('kelas', $selectedKelas)
            ->orderBy('nama_lengkap')
            ->get();

        $presensiRaw = PresensiSiswa::whereIn('siswa_id', $daftarSiswa->pluck('id_siswa'))
            ->whereYear('tanggal', $selectedTahun)
            ->whereMonth('tanggal', $selectedBulan)
            ->get();

        $presensiMap = [];
        foreach ($presensiRaw as $p) {
            $day = (int) \Carbon\Carbon::parse($p->tanggal)->format('j');
            $presensiMap[$p->siswa_id][$day] = $p->status;
        }

        $setting = Setting::first();

        return view('admin.presensi.print_monthly', compact(
            'kelases',
            'selectedKelas',
            'selectedBulan',
            'selectedTahun',
            'daysInMonth',
            'daftarSiswa',
            'presensiMap',
            'setting'
        ));
    }

    public function storePresensi(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'presensi' => ['required', 'array'],
            'presensi.*.status' => ['required', 'in:hadir,sakit,izin,alpa'],
            'presensi.*.keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->presensi as $siswaId => $data) {
            PresensiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        ActivityLog::record('presensi', "Admin/Guru mengisi presensi siswa untuk tanggal {$tanggal}", Auth::user(), Auth::id());

        return back()->with('success', 'Data presensi berhasil disimpan.');
    }

    // ==========================================
    // 3. KELOLA TUGAS & PENILAIAN
    // ==========================================
    public function indexTugas(Request $request)
    {
        $tugas = Tugas::with('creator')->withCount('pengumpulan')->latest()->paginate(10);
        return view('admin.tugas.index', compact('tugas'));
    }

    public function createTugas()
    {
        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');
        return view('admin.tugas.create', compact('kelases'));
    }

    public function storeTugas(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:100'],
            'deadline' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'file_lampiran' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('file_lampiran')) {
            $path = $request->file('file_lampiran')->store('tugas', 'public');
            $validated['file_lampiran'] = $path;
        }

        $validated['created_by'] = Auth::id();

        $tugas = Tugas::create($validated);

        ActivityLog::record('create_tugas', "Admin/Guru membuat tugas baru: {$tugas->judul} ({$tugas->kelas})", Auth::user(), Auth::id());

        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil dipublikasikan ke siswa.');
    }

    public function showTugas(Tugas $tugas)
    {
        $tugas->load('pengumpulan.siswa');
        $siswaKelas = Siswa::where('kelas', $tugas->kelas)->get();

        return view('admin.tugas.show', compact('tugas', 'siswaKelas'));
    }

    public function updateNilaiTugas(Request $request, PengumpulanTugas $pengumpulan)
    {
        $request->validate([
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'catatan_guru' => ['nullable', 'string'],
        ]);

        $pengumpulan->update([
            'nilai' => $request->nilai,
            'catatan_guru' => $request->catatan_guru,
        ]);

        return back()->with('success', 'Nilai tugas berhasil disimpan.');
    }

    public function deleteTugas(Tugas $tugas)
    {
        if ($tugas->file_lampiran && Storage::disk('public')->exists($tugas->file_lampiran)) {
            Storage::disk('public')->delete($tugas->file_lampiran);
        }

        $tugas->delete();

        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }

    // ==========================================
    // 4. REKAPITULASI AKADEMIK & PERANKINGAN
    // ==========================================
    public function getRankedRekapData($selectedKelas)
    {
        $tugasKelas = Tugas::where('kelas', $selectedKelas)->get();

        $siswaList = Siswa::where('kelas', $selectedKelas)
            ->with(['presensi', 'pengumpulanTugas.tugas', 'nilaiManual', 'user'])
            ->get();

        $rekapData = $siswaList->map(function ($siswa) use ($tugasKelas) {
            // 1. Nilai Tugas
            $nilaiTugasList = $siswa->pengumpulanTugas->pluck('nilai')->filter(fn($v) => !is_null($v));
            $nilaiTugas = $nilaiTugasList->count() > 0 ? round($nilaiTugasList->avg(), 1) : 0.0;

            // Rincian Detail Tugas
            $tugasDetails = $tugasKelas->map(function ($tugas) use ($siswa) {
                $pengumpulan = $siswa->pengumpulanTugas->firstWhere('tugas_id', $tugas->id_tugas);
                return [
                    'id_tugas' => $tugas->id_tugas,
                    'judul' => $tugas->judul,
                    'mata_pelajaran' => $tugas->mata_pelajaran,
                    'deadline' => $tugas->deadline,
                    'terkumpul' => !is_null($pengumpulan),
                    'nilai' => $pengumpulan?->nilai,
                    'catatan_guru' => $pengumpulan?->catatan_guru,
                ];
            });

            // Manual Nilai or Fallback Ujian Online
            $manual = $siswa->nilaiManual;

            $identifiers = array_filter([$siswa->nis, $siswa->email, $siswa->user?->username]);

            // Ujian attempts
            $uhAttempts = ExamAttempt::with('exam')
                ->whereHas('exam', fn($q) => $q->where('tipe_ujian', 'uh'))
                ->whereIn('nis_email', $identifiers)
                ->where('status', 'selesai')
                ->get();

            $utsAttempts = ExamAttempt::with('exam')
                ->whereHas('exam', fn($q) => $q->where('tipe_ujian', 'uts'))
                ->whereIn('nis_email', $identifiers)
                ->where('status', 'selesai')
                ->get();

            $uasAttempts = ExamAttempt::with('exam')
                ->whereHas('exam', fn($q) => $q->where('tipe_ujian', 'uas'))
                ->whereIn('nis_email', $identifiers)
                ->where('status', 'selesai')
                ->get();

            // 2. Nilai Ulangan Harian (UH)
            $nilaiUH = $manual?->nilai_uh ?? 0.0;
            if (!$manual || is_null($manual->nilai_uh)) {
                if ($uhAttempts->count() > 0) {
                    $nilaiUH = round($uhAttempts->avg('skor_akhir'), 1);
                }
            }

            // 3. Ujian Tengah Semester (UTS)
            $nilaiUTS = $manual?->nilai_uts ?? 0.0;
            if (!$manual || is_null($manual->nilai_uts)) {
                if ($utsAttempts->count() > 0) {
                    $nilaiUTS = round($utsAttempts->avg('skor_akhir'), 1);
                }
            }

            // 4. Ujian Akhir Semester (UAS)
            $nilaiUAS = $manual?->nilai_uas ?? 0.0;
            if (!$manual || is_null($manual->nilai_uas)) {
                if ($uasAttempts->count() > 0) {
                    $nilaiUAS = round($uasAttempts->avg('skor_akhir'), 1);
                }
            }

            // Rumus Rekap Akademik: (Nilai Tugas + Nilai Ulangan Harian + UTS + UAS) / 4
            $nilaiAkhir = round(($nilaiTugas + $nilaiUH + $nilaiUTS + $nilaiUAS) / 4, 2);

            return [
                'siswa' => $siswa,
                'nilai_tugas' => $nilaiTugas,
                'nilai_uh' => $nilaiUH,
                'nilai_uts' => $nilaiUTS,
                'nilai_uas' => $nilaiUAS,
                'nilai_akhir' => $nilaiAkhir,
                'tugas_details' => $tugasDetails,
                'uh_attempts' => $uhAttempts,
                'uts_attempts' => $utsAttempts,
                'uas_attempts' => $uasAttempts,
            ];
        });

        // Perankingan (Sorted descending by nilai_akhir)
        $sorted = $rekapData->sortByDesc('nilai_akhir')->values();
        return $sorted->map(function ($item, $index) {
            $item['ranking'] = $index + 1;
            return $item;
        });
    }

    public function rekapAkademik(Request $request)
    {
        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');
        if ($kelases->isEmpty()) {
            $kelases = collect(['X MIPA 1']);
        }
        $selectedKelas = $request->get('kelas', $kelases->first());

        $rankedData = $this->getRankedRekapData($selectedKelas);

        return view('admin.rekap.index', compact('kelases', 'selectedKelas', 'rankedData'));
    }

    public function printRekapAkademik(Request $request)
    {
        $kelases = Siswa::select('kelas')->distinct()->pluck('kelas');
        if ($kelases->isEmpty()) {
            $kelases = collect(['X MIPA 1']);
        }
        $selectedKelas = $request->get('kelas', $kelases->first());

        $rankedData = $this->getRankedRekapData($selectedKelas);
        $setting = Setting::first();

        return view('admin.rekap.print', compact('kelases', 'selectedKelas', 'rankedData', 'setting'));
    }

    public function printDetailNilaiSiswa(Siswa $siswa)
    {
        $siswa->load(['user', 'presensi', 'pengumpulanTugas.tugas', 'nilaiManual']);
        $setting = Setting::first();

        // Rekap & Perankingan
        $rankedData = $this->getRankedRekapData($siswa->kelas);
        $myRekap = $rankedData->firstWhere('siswa.id_siswa', $siswa->id_siswa);

        $totalSiswa = $rankedData->count();
        $ranking = $myRekap ? $myRekap['ranking'] : '-';
        $nilaiTugas = $myRekap ? $myRekap['nilai_tugas'] : 0.0;
        $nilaiUH = $myRekap ? $myRekap['nilai_uh'] : 0.0;
        $nilaiUTS = $myRekap ? $myRekap['nilai_uts'] : 0.0;
        $nilaiUAS = $myRekap ? $myRekap['nilai_uas'] : 0.0;
        $nilaiAkhir = $myRekap ? $myRekap['nilai_akhir'] : 0.0;

        // Detail Pengumpulan Tugas
        $pengumpulanTugas = PengumpulanTugas::with('tugas')
            ->where('siswa_id', $siswa->id_siswa)
            ->get();

        // Detail Ujian Online CBT
        $user = $siswa->user;
        $identifiers = array_filter([$siswa->nis, $siswa->email, $user?->username]);
        $examAttempts = ExamAttempt::with('exam')
            ->whereIn('nis_email', $identifiers)
            ->where('status', 'selesai')
            ->get();

        // Stat Presensi
        $statHadir = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'hadir')->count();
        $statSakit = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'sakit')->count();
        $statIzin = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'izin')->count();
        $statAlpa = PresensiSiswa::where('siswa_id', $siswa->id_siswa)->where('status', 'alpa')->count();
        $totalPresensi = $statHadir + $statSakit + $statIzin + $statAlpa;
        $persenHadir = $totalPresensi > 0 ? round(($statHadir / $totalPresensi) * 100, 1) : 100.0;

        // Determine Predikat & Letter Grade
        $predikat = 'Cukup';
        $huruf = 'C';
        if ($nilaiAkhir >= 85) {
            $predikat = 'Sangat Memuaskan';
            $huruf = 'A';
        } elseif ($nilaiAkhir >= 75) {
            $predikat = 'Memuaskan';
            $huruf = 'B';
        } elseif ($nilaiAkhir >= 65) {
            $predikat = 'Cukup';
            $huruf = 'C';
        } else {
            $predikat = 'Kurang';
            $huruf = 'D';
        }

        return view('admin.rekap.print_detail', compact(
            'siswa',
            'setting',
            'myRekap',
            'totalSiswa',
            'ranking',
            'nilaiTugas',
            'nilaiUH',
            'nilaiUTS',
            'nilaiUAS',
            'nilaiAkhir',
            'pengumpulanTugas',
            'examAttempts',
            'statHadir',
            'statSakit',
            'statIzin',
            'statAlpa',
            'persenHadir',
            'predikat',
            'huruf'
        ));
    }

    public function storeNilaiManual(Request $request)
    {
        $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*.uh' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($request->nilai as $siswaId => $scores) {
            \App\Models\NilaiSiswaManual::updateOrCreate(
                ['siswa_id' => $siswaId],
                [
                    'nilai_uh' => $scores['uh'] !== '' ? $scores['uh'] : null,
                    'nilai_uts' => $scores['uts'] !== '' ? $scores['uts'] : null,
                    'nilai_uas' => $scores['uas'] !== '' ? $scores['uas'] : null,
                ]
            );
        }

        ActivityLog::record('update_nilai', "Admin/Guru memperbarui data nilai UH, UTS, UAS siswa", Auth::user(), Auth::id());

        return back()->with('success', 'Komponen Nilai Siswa (UH, UTS, UAS) berhasil disimpan & perankingan otomatis diperbarui.');
    }
}
