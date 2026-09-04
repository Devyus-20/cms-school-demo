<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamPublicController extends Controller
{
    /** Halaman Katalog Ujian Online Aktif */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login.siswa')->with('error', 'Silakan masuk ke Portal Siswa terlebih dahulu untuk mengikuti Ujian Online.');
        }

        if (auth()->user()?->role?->name !== 'Siswa') {
            return redirect()->route('dashboard')->with('error', 'Fitur Ujian Online ini khusus untuk akun Siswa.');
        }

        $now = Carbon::now();
        
        $exams = Exam::where('aktif', true)
            ->latest('id_exam')
            ->get();

        return view('public.ujian.index', compact('exams', 'now'));
    }

    /** Form Konfirmasi Data Siswa (Nama, NIS, Kelas, Token) */
    public function confirm(Exam $exam)
    {
        if (!$exam->aktif) {
            return redirect()->route('public.ujian.index')->with('error', 'Ujian ini sudah tidak aktif.');
        }

        $now = Carbon::now();
        if ($exam->waktu_mulai && $now->lt($exam->waktu_mulai)) {
            return redirect()->route('public.ujian.index')->with('error', 'Ujian ini belum dimulai.');
        }

        if ($exam->waktu_selesai && $now->gt($exam->waktu_selesai)) {
            return redirect()->route('public.ujian.index')->with('error', 'Ujian ini telah berakhir.');
        }

        return view('public.ujian.confirm', compact('exam'));
    }

    /** Memulai Sesi Ujian (Create Attempt) */
    public function start(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'nis_email'    => 'required|string|max:255',
            'kelas'        => 'required|string|max:255',
            'token'        => 'nullable|string',
        ]);

        if ($exam->token && strtoupper(trim($request->token)) !== strtoupper(trim($exam->token))) {
            return back()->withInput()->with('error', 'Token Ujian yang Anda masukkan salah.');
        }

        $nisEmail = trim($validated['nis_email']);

        // Jika ujian ini membatasi hanya peserta terdaftar (Whitelist)
        if ($exam->batasi_peserta) {
            $participantInfo = $exam->participants()
                ->where('nis_email', $nisEmail)
                ->first();

            if (!$participantInfo) {
                return back()->withInput()->with('error', "NIS/Email ('{$nisEmail}') tidak terdaftar sebagai peserta ujian ini. Silakan hubungi guru/pengawas.");
            }

            // Gunakan Nama & Kelas resmi dari whitelist jika ada
            if ($participantInfo->nama) {
                $validated['nama_peserta'] = $participantInfo->nama;
            }
            if ($participantInfo->kelas) {
                $validated['kelas'] = $participantInfo->kelas;
            }
        }

        // Cek apakah siswa dengan NIS/Email ini sudah pernah membuat sesi ujian ini
        $existingAttempt = ExamAttempt::where('id_exam', $exam->id_exam)
            ->where('nis_email', $nisEmail)
            ->latest('id_attempt')
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status === 'selesai') {
                return back()->withInput()->with('error', 'Anda sudah selesai mengerjakan ujian ini dan tidak dapat mengulangnya kembali.');
            }

            // Jika sesi sebelumnya masih berlangsung, cek sisa waktu
            $waktuSelesaiMaksimal = $existingAttempt->waktu_mulai->addMinutes($exam->durasi_menit);
            if (Carbon::now()->gte($waktuSelesaiMaksimal)) {
                $this->processFinish($existingAttempt);
                return redirect()->route('public.ujian.result', $existingAttempt->id_attempt)->with('error', 'Waktu ujian Anda telah habis.');
            }

            // Lanjutkan sesi ujian yang berlangsung
            return redirect()->route('public.ujian.session', $existingAttempt->id_attempt);
        }

        // Buat sesi pengerjaan ujian baru jika belum pernah
        $attempt = ExamAttempt::create([
            'id_exam'      => $exam->id_exam,
            'nama_peserta' => $validated['nama_peserta'],
            'nis_email'    => $nisEmail,
            'kelas'        => $validated['kelas'],
            'waktu_mulai'  => Carbon::now(),
            'status'       => 'berlangsung',
            'skor_akhir'   => 0,
        ]);

        return redirect()->route('public.ujian.session', $attempt->id_attempt);
    }

    /** Halaman Sheet Lembar Kerja Ujian Siswa */
    public function session(ExamAttempt $attempt)
    {
        if ($attempt->status === 'selesai') {
            return redirect()->route('public.ujian.result', $attempt->id_attempt);
        }

        $exam = $attempt->exam;

        // Cek durasi & sisa waktu (dalam detik)
        $waktuSelesaiMaksimal = $attempt->waktu_mulai->addMinutes($exam->durasi_menit);
        $sisaDetik = Carbon::now()->diffInSeconds($waktuSelesaiMaksimal, false);

        if ($sisaDetik <= 0) {
            // Waktu habis, otomatis selesaikan dan langsung tampilkan nilai
            return $this->processFinish($attempt);
        }

        // Ambil daftar soal
        $questionsQuery = $exam->questions();
        if ($exam->acak_soal) {
            $questions = $questionsQuery->inRandomOrder()->get();
        } else {
            $questions = $questionsQuery->orderBy('urutan')->get();
        }

        // Jawaban tersimpan sejauh ini
        $answers = ExamAnswer::where('id_attempt', $attempt->id_attempt)
            ->pluck('jawaban_peserta', 'id_question')
            ->toArray();

        return view('public.ujian.session', compact('attempt', 'exam', 'questions', 'answers', 'sisaDetik'));
    }

    /** Simpan Jawaban via AJAX atau Form Submit */
    public function saveAnswer(Request $request, ExamAttempt $attempt)
    {
        if ($attempt->status === 'selesai') {
            return response()->json([
                'status' => 'expired',
                'redirect' => route('public.ujian.result', $attempt->id_attempt)
            ], 400);
        }

        // Cek jika waktu sudah habis saat menyimpan jawaban
        $waktuSelesaiMaksimal = $attempt->waktu_mulai->addMinutes($attempt->exam->durasi_menit);
        if (Carbon::now()->gte($waktuSelesaiMaksimal)) {
            $this->processFinish($attempt);
            return response()->json([
                'status' => 'expired',
                'redirect' => route('public.ujian.result', $attempt->id_attempt)
            ], 400);
        }

        $idQuestion = $request->input('id_question');
        $jawaban = $request->input('jawaban');

        if (!$idQuestion) {
            return response()->json(['status' => 'error', 'message' => 'ID Soal tidak valid'], 400);
        }

        $question = ExamQuestion::find($idQuestion);
        if (!$question) {
            return response()->json(['status' => 'error', 'message' => 'Soal tidak ditemukan'], 404);
        }

        // Evaluasi otomatis jika Pilihan Ganda
        $isBenar = false;
        $nilaiSoal = 0;

        if ($question->jenis === 'pilihan_ganda') {
            if ($question->kunci_jawaban && strtoupper(trim($jawaban)) === strtoupper(trim($question->kunci_jawaban))) {
                $isBenar = true;
                $nilaiSoal = $question->bobot_nilai;
            }
        }

        ExamAnswer::updateOrCreate(
            [
                'id_attempt'  => $attempt->id_attempt,
                'id_question' => $idQuestion,
            ],
            [
                'jawaban_peserta' => $jawaban,
                'is_benar'        => $isBenar,
                'nilai_soal'      => $nilaiSoal,
            ]
        );

        return response()->json(['status' => 'success']);
    }

    /** Submit Selesai Ujian */
    public function finish(ExamAttempt $attempt)
    {
        return $this->processFinish($attempt);
    }

    private function processFinish(ExamAttempt $attempt)
    {
        if ($attempt->status === 'selesai') {
            return redirect()->route('public.ujian.result', $attempt->id_attempt);
        }

        $exam = $attempt->exam;

        // Hitung total skor akhir
        $totalSkorPerolehan = ExamAnswer::where('id_attempt', $attempt->id_attempt)->sum('nilai_soal');
        $totalBobotMaksimal = $exam->questions()->sum('bobot_nilai');

        $skorAkhir = 0;
        if ($totalBobotMaksimal > 0) {
            $skorAkhir = round(($totalSkorPerolehan / $totalBobotMaksimal) * 100, 2);
        }

        $attempt->update([
            'waktu_selesai' => Carbon::now(),
            'status'        => 'selesai',
            'skor_akhir'    => $skorAkhir,
        ]);

        return redirect()->route('public.ujian.result', $attempt->id_attempt);
    }

    /** Halaman Hasil Nilai Ujian Siswa */
    public function result(ExamAttempt $attempt)
    {
        $attempt->load(['exam', 'answers.question']);
        return view('public.ujian.result', compact('attempt'));
    }
}
