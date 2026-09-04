<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\PpdbRegistration;
use App\Models\PresensiSiswa;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportAdminController extends Controller
{
    /**
     * Dashboard Pusat Laporan Terpadu
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'siswa');
        $setting = Setting::first();

        // Data filter umum
        $kelases = Siswa::select('kelas')->whereNotNull('kelas')->where('kelas', '!=', '')->distinct()->pluck('kelas');
        $tahunMasuks = Siswa::select('tahun_masuk')->whereNotNull('tahun_masuk')->where('tahun_masuk', '!=', '')->distinct()->pluck('tahun_masuk');
        $exams = Exam::orderBy('id_exam', 'desc')->get();
        $jurusanList = $setting?->jurusan_list ?? ['MIPA', 'IPS', 'Keagamaan'];
        $adminUsers = User::orderBy('name')->get();

        $data = [];
        $stats = [];

        switch ($type) {
            case 'siswa':
                $query = Siswa::with('user');
                if ($request->filled('kelas')) {
                    $query->where('kelas', $request->kelas);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('jenis_kelamin')) {
                    $query->where('jenis_kelamin', $request->jenis_kelamin);
                }
                if ($request->filled('tahun_masuk')) {
                    $query->where('tahun_masuk', $request->tahun_masuk);
                }
                if ($request->filled('search')) {
                    $s = $request->search;
                    $query->where(function ($q) use ($s) {
                        $q->where('nama_lengkap', 'like', "%{$s}%")
                          ->orWhere('nis', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                    });
                }

                $stats = [
                    'total' => (clone $query)->count(),
                    'aktif' => (clone $query)->where('status', 'aktif')->count(),
                    'pending' => (clone $query)->where('status', 'pending_register')->count(),
                    'alumni' => (clone $query)->where('status', 'alumni')->count(),
                    'laki' => (clone $query)->where('jenis_kelamin', 'L')->count(),
                    'perempuan' => (clone $query)->where('jenis_kelamin', 'P')->count(),
                ];

                $data = $query->orderBy('kelas')->orderBy('nama_lengkap')->paginate(15)->withQueryString();
                break;

            case 'nilai':
                $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');
                $rekapController = new SiswaAdminController();
                $rankedData = $rekapController->getRankedRekapData($selectedKelas);

                if ($request->filled('search')) {
                    $s = strtolower($request->search);
                    $rankedData = $rankedData->filter(function ($item) use ($s) {
                        return str_contains(strtolower($item['siswa']->nama_lengkap), $s)
                            || str_contains(strtolower($item['siswa']->nis), $s);
                    })->values();
                }

                $scores = $rankedData->pluck('nilai_akhir');
                $stats = [
                    'total_siswa' => $rankedData->count(),
                    'rata_rata' => $scores->count() > 0 ? round($scores->avg(), 2) : 0,
                    'tertinggi' => $scores->count() > 0 ? $scores->max() : 0,
                    'terendah' => $scores->count() > 0 ? $scores->min() : 0,
                    'peringkat_1' => $rankedData->first()['siswa']->nama_lengkap ?? '-',
                ];

                $data = $rankedData;
                break;

            case 'presensi':
                $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');
                $bulan = (int) $request->get('bulan', date('m'));
                $tahun = (int) $request->get('tahun', date('Y'));

                $siswaList = Siswa::where('kelas', $selectedKelas)
                    ->orderBy('nama_lengkap')
                    ->get();

                $siswaIds = $siswaList->pluck('id_siswa');

                $presensiRecords = PresensiSiswa::whereIn('siswa_id', $siswaIds)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->get();

                $rekapPresensi = $siswaList->map(function ($s) use ($presensiRecords) {
                    $records = $presensiRecords->where('siswa_id', $s->id_siswa);
                    $hadir = $records->where('status', 'hadir')->count();
                    $izin = $records->where('status', 'izin')->count();
                    $sakit = $records->where('status', 'sakit')->count();
                    $alpa = $records->where('status', 'alpa')->count();
                    $total = $hadir + $izin + $sakit + $alpa;
                    $persenHadir = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                    return [
                        'siswa' => $s,
                        'hadir' => $hadir,
                        'izin' => $izin,
                        'sakit' => $sakit,
                        'alpa' => $alpa,
                        'total' => $total,
                        'persen' => $persenHadir,
                    ];
                });

                $totalHadirSemua = $rekapPresensi->sum('hadir');
                $totalSesiSemua = $rekapPresensi->sum('total');
                $avgPersen = $totalSesiSemua > 0 ? round(($totalHadirSemua / $totalSesiSemua) * 100, 1) : 0;

                $stats = [
                    'total_siswa' => $siswaList->count(),
                    'total_hadir' => $totalHadirSemua,
                    'total_izin' => $rekapPresensi->sum('izin'),
                    'total_sakit' => $rekapPresensi->sum('sakit'),
                    'total_alpa' => $rekapPresensi->sum('alpa'),
                    'rata_kehadiran' => $avgPersen,
                ];

                $data = $rekapPresensi;
                break;

            case 'ujian':
                $selectedExamId = $request->get('exam_id', $exams->first()?->id_exam);
                $selectedExam = $exams->firstWhere('id_exam', $selectedExamId);

                $attemptsQuery = ExamAttempt::with('exam')
                    ->where('id_exam', $selectedExamId)
                    ->where('status', 'selesai');

                if ($request->filled('search')) {
                    $s = $request->search;
                    $attemptsQuery->where(function ($q) use ($s) {
                        $q->where('nama_peserta', 'like', "%{$s}%")
                          ->orWhere('nis_email', 'like', "%{$s}%");
                    });
                }

                $attempts = $attemptsQuery->orderBy('skor_akhir', 'desc')->get();
                $kkm = $selectedExam?->kkm ?? 75;

                $stats = [
                    'total_peserta' => $attempts->count(),
                    'lulus' => $attempts->where('skor_akhir', '>=', $kkm)->count(),
                    'belum_lulus' => $attempts->where('skor_akhir', '<', $kkm)->count(),
                    'rata_rata' => $attempts->count() > 0 ? round($attempts->avg('skor_akhir'), 1) : 0,
                    'tertinggi' => $attempts->count() > 0 ? $attempts->max('skor_akhir') : 0,
                    'terendah' => $attempts->count() > 0 ? $attempts->min('skor_akhir') : 0,
                    'kkm' => $kkm,
                ];

                $data = [
                    'exam' => $selectedExam,
                    'attempts' => $attempts,
                ];
                break;

            case 'ppdb':
                $query = PpdbRegistration::query();
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('jurusan')) {
                    $query->where('jurusan', $request->jurusan);
                }
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                if ($request->filled('search')) {
                    $s = $request->search;
                    $query->where(function ($q) use ($s) {
                        $q->where('nama_lengkap', 'like', "%{$s}%")
                          ->orWhere('no_pendaftaran', 'like', "%{$s}%")
                          ->orWhere('nisn', 'like', "%{$s}%")
                          ->orWhere('sekolah_asal', 'like', "%{$s}%");
                    });
                }

                $stats = [
                    'total' => (clone $query)->count(),
                    'pending' => (clone $query)->where('status', 'pending')->count(),
                    'verified' => (clone $query)->where('status', 'verified')->count(),
                    'accepted' => (clone $query)->where('status', 'accepted')->count(),
                    'reserved' => (clone $query)->where('status', 'reserved')->count(),
                    'rejected' => (clone $query)->where('status', 'rejected')->count(),
                ];

                $data = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
                break;

            case 'activity':
                $query = ActivityLog::with('user');
                if ($request->filled('user_id')) {
                    $query->where('user_id', $request->user_id);
                }
                if ($request->filled('action')) {
                    $query->where('action', $request->action);
                }
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                }
                if ($request->filled('search')) {
                    $s = $request->search;
                    $query->where('description', 'like', "%{$s}%");
                }

                $stats = [
                    'total' => (clone $query)->count(),
                    'today' => (clone $query)->whereDate('created_at', Carbon::today())->count(),
                    'users_count' => (clone $query)->distinct('user_id')->count('user_id'),
                ];

                $data = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
                break;
        }

        return view('admin.reports.index', compact(
            'type',
            'setting',
            'kelases',
            'tahunMasuks',
            'exams',
            'jurusanList',
            'adminUsers',
            'data',
            'stats'
        ));
    }

    // ==========================================
    // CETAK LEMBAR RESMI BER-KOP (PRINT / PDF)
    // ==========================================

    public function printSiswa(Request $request)
    {
        $setting = Setting::first();
        $query = Siswa::with('user');

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        $siswaList = $query->orderBy('kelas')->orderBy('nama_lengkap')->get();
        $filterDesc = [
            'Kelas' => $request->filled('kelas') ? $request->kelas : 'Semua Kelas',
            'Status' => $request->filled('status') ? ucfirst(str_replace('_', ' ', $request->status)) : 'Semua Status',
            'Tahun Masuk' => $request->filled('tahun_masuk') ? $request->tahun_masuk : 'Semua Angkatan',
        ];

        return view('admin.reports.print-siswa', compact('setting', 'siswaList', 'filterDesc'));
    }

    public function printNilai(Request $request)
    {
        $setting = Setting::first();
        $kelases = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');

        $rekapController = new SiswaAdminController();
        $rankedData = $rekapController->getRankedRekapData($selectedKelas);

        return view('admin.reports.print-nilai', compact('setting', 'rankedData', 'selectedKelas', 'kelases'));
    }

    public function printPresensi(Request $request)
    {
        $setting = Setting::first();
        $kelases = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $siswaList = Siswa::where('kelas', $selectedKelas)->orderBy('nama_lengkap')->get();
        $siswaIds = $siswaList->pluck('id_siswa');
        $presensiRecords = PresensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $rekapPresensi = $siswaList->map(function ($s) use ($presensiRecords) {
            $records = $presensiRecords->where('siswa_id', $s->id_siswa);
            $hadir = $records->where('status', 'hadir')->count();
            $izin = $records->where('status', 'izin')->count();
            $sakit = $records->where('status', 'sakit')->count();
            $alpa = $records->where('status', 'alpa')->count();
            $total = $hadir + $izin + $sakit + $alpa;
            $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            return [
                'siswa' => $s,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'total' => $total,
                'persen' => $persen,
            ];
        });

        return view('admin.reports.print-presensi', compact(
            'setting',
            'rekapPresensi',
            'selectedKelas',
            'bulan',
            'tahun',
            'namaBulan',
            'kelases'
        ));
    }

    public function printUjian(Request $request)
    {
        $setting = Setting::first();
        $examId = $request->get('exam_id');
        $exam = $examId ? Exam::find($examId) : null;
        if (!$exam) {
            $exam = Exam::first();
        }

        if (!$exam) {
            return back()->with('error', 'Belum ada data ujian yang tersedia.');
        }

        $attempts = $exam->attempts()
            ->where('status', 'selesai')
            ->latest('waktu_selesai')
            ->get();

        $questionsCount = $exam->questions()->count();

        // Hitung Statistik Nilai Ujian (Identik dengan modul CBT Admin)
        $kkm          = $exam->kkm ?? 75;
        $totalPeserta = $attempts->count();
        $avgScore     = $totalPeserta > 0 ? round($attempts->avg('skor_akhir'), 2) : 0;
        $maxScore     = $totalPeserta > 0 ? $attempts->max('skor_akhir') : 0;
        $minScore     = $totalPeserta > 0 ? $attempts->min('skor_akhir') : 0;
        $lulusCount   = $attempts->where('skor_akhir', '>=', $kkm)->count();
        $gagalCount   = $totalPeserta - $lulusCount;

        return view('admin.reports.print-ujian', compact(
            'exam',
            'attempts',
            'questionsCount',
            'setting',
            'totalPeserta',
            'avgScore',
            'maxScore',
            'minScore',
            'lulusCount',
            'gagalCount'
        ));
    }

    public function printPpdb(Request $request)
    {
        $setting = Setting::first();
        $query = PpdbRegistration::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();
        $filterDesc = [
            'Status' => $request->filled('status') ? ucfirst($request->status) : 'Semua Status',
            'Jurusan' => $request->filled('jurusan') ? $request->jurusan : 'Semua Jurusan',
            'Periode' => ($request->filled('start_date') && $request->filled('end_date')) ? "{$request->start_date} s/d {$request->end_date}" : 'Semua Periode',
        ];

        return view('admin.reports.print-ppdb', compact('setting', 'registrations', 'filterDesc'));
    }

    public function printActivity(Request $request)
    {
        $setting = Setting::first();
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->limit(300)->get();

        return view('admin.reports.print-activity', compact('setting', 'logs'));
    }

    // ==========================================
    // EKSPOR CSV / EXCEL FLEKSIBEL
    // ==========================================

    public function exportSiswaCsv(Request $request): StreamedResponse
    {
        $query = Siswa::query();
        if ($request->filled('kelas')) $query->where('kelas', $request->kelas);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('jenis_kelamin')) $query->where('jenis_kelamin', $request->jenis_kelamin);
        if ($request->filled('tahun_masuk')) $query->where('tahun_masuk', $request->tahun_masuk);

        $siswaList = $query->orderBy('kelas')->orderBy('nama_lengkap')->get();
        $filename = 'Laporan_Data_Siswa_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($siswaList) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM untuk Microsoft Excel

            fputcsv($handle, ['No', 'NIS', 'NISN', 'Nama Lengkap', 'L/P', 'Kelas', 'Tahun Masuk', 'Status', 'Email', 'Telepon', 'Alamat']);

            foreach ($siswaList as $idx => $s) {
                fputcsv($handle, [
                    $idx + 1,
                    $s->nis,
                    $s->nisn ?? '-',
                    $s->nama_lengkap,
                    $s->jenis_kelamin,
                    $s->kelas,
                    $s->tahun_masuk,
                    $s->status,
                    $s->email,
                    $s->telepon ?? '-',
                    $s->alamat ?? '-',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportNilaiCsv(Request $request): StreamedResponse
    {
        $kelases = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');

        $rekapController = new SiswaAdminController();
        $rankedData = $rekapController->getRankedRekapData($selectedKelas);
        $filename = 'Laporan_Rekap_Nilai_Kelas_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $selectedKelas) . '_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rankedData, $selectedKelas) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Ranking', 'NIS', 'Nama Siswa', 'Kelas', 'Rata-rata Tugas', 'Ulangan Harian (UH)', 'UTS', 'UAS', 'Nilai Akhir', 'Predikat']);

            foreach ($rankedData as $idx => $item) {
                $na = $item['nilai_akhir'];
                $predikat = $na >= 90 ? 'A (Sangat Baik)' : ($na >= 80 ? 'B (Baik)' : ($na >= 70 ? 'C (Cukup)' : 'D (Perlu Bimbingan)'));

                fputcsv($handle, [
                    $idx + 1,
                    $item['siswa']->nis,
                    $item['siswa']->nama_lengkap,
                    $selectedKelas,
                    $item['nilai_tugas'],
                    $item['nilai_uh'],
                    $item['nilai_uts'],
                    $item['nilai_uas'],
                    $na,
                    $predikat,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPresensiCsv(Request $request): StreamedResponse
    {
        $kelases = Siswa::select('kelas')->whereNotNull('kelas')->distinct()->pluck('kelas');
        $selectedKelas = $request->get('kelas', $kelases->first() ?? 'X-A');
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $siswaList = Siswa::where('kelas', $selectedKelas)->orderBy('nama_lengkap')->get();
        $siswaIds = $siswaList->pluck('id_siswa');
        $presensiRecords = PresensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $filename = 'Laporan_Presensi_Kelas_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $selectedKelas) . "_{$tahun}_{$bulan}.csv";

        return response()->streamDownload(function () use ($siswaList, $presensiRecords, $selectedKelas, $bulan, $tahun) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Bulan', 'Tahun', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Total Pertemuan', '% Kehadiran']);

            foreach ($siswaList as $idx => $s) {
                $records = $presensiRecords->where('siswa_id', $s->id_siswa);
                $hadir = $records->where('status', 'hadir')->count();
                $izin = $records->where('status', 'izin')->count();
                $sakit = $records->where('status', 'sakit')->count();
                $alpa = $records->where('status', 'alpa')->count();
                $total = $hadir + $izin + $sakit + $alpa;
                $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                fputcsv($handle, [
                    $idx + 1,
                    $s->nis,
                    $s->nama_lengkap,
                    $selectedKelas,
                    $bulan,
                    $tahun,
                    $hadir,
                    $izin,
                    $sakit,
                    $alpa,
                    $total,
                    $persen . '%',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportUjianCsv(Request $request): StreamedResponse
    {
        $examId = $request->get('exam_id');
        $exam = $examId ? Exam::find($examId) : null;
        if (!$exam) {
            $exam = Exam::first();
        }

        if (!$exam) {
            abort(404, 'Belum ada data ujian yang tersedia.');
        }

        $questions = $exam->questions()->orderBy('id_question')->get();
        $attempts  = $exam->attempts()
            ->where('status', 'selesai')
            ->with(['answers.question'])
            ->latest('waktu_selesai')
            ->get();

        $filename = 'Rekap_Nilai_' . \Illuminate\Support\Str::slug($exam->judul) . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($exam, $questions, $attempts) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['REKAPITULASI HASIL & JAWABAN UJIAN ONLINE']);
            fputcsv($file, ['Judul Ujian', $exam->judul]);
            fputcsv($file, ['Mata Pelajaran', $exam->mata_pelajaran]);
            fputcsv($file, ['Durasi Ujian', $exam->durasi_menit . ' Menit']);
            fputcsv($file, ['Total Soal', $questions->count() . ' Soal']);
            fputcsv($file, ['Tanggal Rekap', date('d-m-Y H:i:s')]);
            fputcsv($file, []);

            $tableHeader = [
                'No', 'Nama Peserta', 'NIS / Email', 'Kelas', 'Waktu Mulai', 'Waktu Selesai',
                'Durasi (Menit)', 'Jawaban Benar (PG)', 'Jawaban Salah (PG)', 'Skor PG', 'Skor Essay',
                'Skor Akhir (Nilai 100)', 'Status Keterangan'
            ];

            foreach ($questions as $idx => $q) {
                $num = $idx + 1;
                $tableHeader[] = "Soal {$num} (Jwb)";
                $tableHeader[] = "Soal {$num} (Kunci)";
                $tableHeader[] = "Soal {$num} (Nilai)";
            }

            fputcsv($file, $tableHeader);

            foreach ($attempts as $index => $att) {
                $answersByQ = $att->answers->keyBy('id_question');
                $durasiMenit = ($att->waktu_mulai && $att->waktu_selesai)
                    ? $att->waktu_mulai->diffInMinutes($att->waktu_selesai)
                    : 0;

                $jmlBenarPG = 0;
                $jmlSalahPG = 0;
                $skorPG     = 0;
                $skorEssay  = 0;

                foreach ($questions as $q) {
                    $ans = $answersByQ->get($q->id_question);
                    if ($ans) {
                        if ($q->jenis === 'pilihan_ganda') {
                            if ($ans->is_benar) {
                                $jmlBenarPG++;
                                $skorPG += $ans->nilai_soal;
                            } else {
                                $jmlSalahPG++;
                            }
                        } else {
                            $skorEssay += $ans->nilai_soal;
                        }
                    }
                }

                $statusLulus = ($att->skor_akhir >= ($exam->kkm ?? 75)) ? 'TUNTAS' : 'BELUM TUNTAS';

                $row = [
                    $index + 1,
                    $att->nama_peserta,
                    $att->nis_email,
                    $att->kelas,
                    $att->waktu_mulai ? $att->waktu_mulai->format('d/m/Y H:i') : '-',
                    $att->waktu_selesai ? $att->waktu_selesai->format('d/m/Y H:i') : '-',
                    $durasiMenit,
                    $jmlBenarPG,
                    $jmlSalahPG,
                    $skorPG,
                    $skorEssay,
                    number_format($att->skor_akhir, 2),
                    $statusLulus,
                ];

                foreach ($questions as $q) {
                    $ans = $answersByQ->get($q->id_question);
                    if ($ans) {
                        $jwb = $ans->jawaban_peserta ?? '-';
                        $kunci = $q->kunci_jawaban ?? '-';
                        $nilaiSoal = $ans->nilai_soal ?? 0;
                        $row[] = strtoupper($jwb);
                        $row[] = strtoupper($kunci);
                        $row[] = $nilaiSoal;
                    } else {
                        $row[] = '-';
                        $row[] = strtoupper($q->kunci_jawaban ?? '-');
                        $row[] = 0;
                    }
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    public function exportPpdbCsv(Request $request): StreamedResponse
    {
        $query = PpdbRegistration::query();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('jurusan')) $query->where('jurusan', $request->jurusan);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $ppdbList = $query->orderBy('created_at', 'desc')->get();
        $filename = 'Laporan_Pendaftar_PPDB_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($ppdbList) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No', 'No Pendaftaran', 'Nama Lengkap', 'NISN', 'L/P', 'Tempat Lahir', 'Tanggal Lahir',
                'Agama', 'Sekolah Asal', 'Nama Orang Tua', 'No WhatsApp/HP', 'Email', 'Pilihan Jurusan', 'Status', 'Tanggal Pendaftaran'
            ]);

            foreach ($ppdbList as $idx => $p) {
                fputcsv($handle, [
                    $idx + 1,
                    $p->no_pendaftaran,
                    $p->nama_lengkap,
                    $p->nisn ?? '-',
                    $p->jenis_kelamin,
                    $p->tempat_lahir ?? '-',
                    $p->tanggal_lahir ? Carbon::parse($p->tanggal_lahir)->format('d/m/Y') : '-',
                    $p->agama ?? '-',
                    $p->sekolah_asal ?? '-',
                    $p->nama_orang_tua ?? '-',
                    $p->no_hp ?? '-',
                    $p->email ?? '-',
                    $p->jurusan ?? '-',
                    strtoupper($p->status),
                    $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportActivityCsv(Request $request): StreamedResponse
    {
        $query = ActivityLog::with('user');
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $logs = $query->orderBy('created_at', 'desc')->limit(1000)->get();
        $filename = 'Laporan_Audit_Activity_Logs_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Waktu', 'Pengguna (Admin)', 'Jenis Aksi', 'Deskripsi Aktivitas', 'IP Address']);

            foreach ($logs as $idx => $l) {
                fputcsv($handle, [
                    $idx + 1,
                    $l->created_at ? $l->created_at->format('d/m/Y H:i:s') : '-',
                    $l->user?->name ?? ($l->user?->username ?? 'System/Guest'),
                    strtoupper($l->action),
                    $l->description,
                    $l->ip_address ?? '-',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
