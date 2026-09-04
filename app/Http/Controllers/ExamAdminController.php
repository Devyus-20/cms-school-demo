<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamParticipant;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;

class ExamAdminController extends Controller
{
    public function index()
    {
        $exams = Exam::withCount(['questions', 'attempts', 'participants'])
            ->latest('id_exam')
            ->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'mata_pelajaran'  => 'required|string|max:255',
            'tipe_ujian'      => 'required|in:uh,uts,uas,lainnya',
            'deskripsi'       => 'nullable|string',
            'durasi_menit'    => 'required|integer|min:1',
            'kkm'             => 'nullable|integer|min:0|max:100',
            'waktu_mulai'     => 'nullable|date',
            'waktu_selesai'   => 'nullable|date|after_or_equal:waktu_mulai',
            'token'           => 'nullable|string|max:20',
            'acak_soal'       => 'nullable|boolean',
            'tampilkan_nilai' => 'nullable|boolean',
            'aktif'           => 'nullable|boolean',
            'batasi_peserta'  => 'nullable|boolean',
        ]);

        $validated['kkm']             = $request->filled('kkm') ? (int)$request->input('kkm') : 75;
        $validated['acak_soal']       = $request->has('acak_soal');
        $validated['tampilkan_nilai'] = $request->has('tampilkan_nilai');
        $validated['aktif']           = $request->has('aktif');
        $validated['batasi_peserta']  = $request->has('batasi_peserta');

        $exam = Exam::create($validated);

        if ($exam->batasi_peserta) {
            return redirect()->route('admin.exams.participants', $exam)->with('success', 'Ujian berhasil dibuat. Silakan tambahkan daftar peserta yang terdaftar.');
        }

        return redirect()->route('admin.exams.index')->with('success', 'Ujian online berhasil dibuat.');
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:255',
            'mata_pelajaran'  => 'required|string|max:255',
            'tipe_ujian'      => 'required|in:uh,uts,uas,lainnya',
            'deskripsi'       => 'nullable|string',
            'durasi_menit'    => 'required|integer|min:1',
            'kkm'             => 'nullable|integer|min:0|max:100',
            'waktu_mulai'     => 'nullable|date',
            'waktu_selesai'   => 'nullable|date|after_or_equal:waktu_mulai',
            'token'           => 'nullable|string|max:20',
            'acak_soal'       => 'nullable|boolean',
            'tampilkan_nilai' => 'nullable|boolean',
            'aktif'           => 'nullable|boolean',
            'batasi_peserta'  => 'nullable|boolean',
        ]);

        $validated['kkm']             = $request->filled('kkm') ? (int)$request->input('kkm') : 75;
        $validated['acak_soal']       = $request->has('acak_soal');
        $validated['tampilkan_nilai'] = $request->has('tampilkan_nilai');
        $validated['aktif']           = $request->has('aktif');
        $validated['batasi_peserta']  = $request->has('batasi_peserta');

        $exam->update($validated);

        return redirect()->route('admin.exams.index')->with('success', 'Ujian online berhasil diperbarui.');
    }

    /** Kelola Peserta Terdaftar (Whitelist) */
    public function participants(Exam $exam)
    {
        $participants = $exam->participants()->latest('id_participant')->paginate(30);
        return view('admin.exams.participants', compact('exam', 'participants'));
    }

    public function storeParticipant(Request $request, Exam $exam)
    {
        // Mendukung input tunggal atau bulk paste NIS/Nama
        if ($request->filled('bulk_data')) {
            $lines = explode("\n", $request->bulk_data);
            $added = 0;

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Format per baris: NIS/Email, Nama, Kelas (dipisahkan koma atau tab atau titik koma)
                $parts = preg_split('/[,;\t]/', $line);
                $nis   = trim($parts[0] ?? '');
                $nama  = trim($parts[1] ?? '');
                $kelas = trim($parts[2] ?? '');

                if (!empty($nis)) {
                    ExamParticipant::updateOrCreate(
                        [
                            'id_exam'   => $exam->id_exam,
                            'nis_email' => $nis,
                        ],
                        [
                            'nama'  => $nama ?: null,
                            'kelas' => $kelas ?: null,
                        ]
                    );
                    $added++;
                }
            }

            return redirect()->route('admin.exams.participants', $exam)->with('success', "Berhasil menambahkan/memperbarui {$added} peserta terdaftar.");
        }

        $validated = $request->validate([
            'nis_email' => 'required|string|max:255',
            'nama'      => 'nullable|string|max:255',
            'kelas'     => 'nullable|string|max:255',
        ]);

        ExamParticipant::updateOrCreate(
            [
                'id_exam'   => $exam->id_exam,
                'nis_email' => trim($validated['nis_email']),
            ],
            [
                'nama'  => $validated['nama'] ? trim($validated['nama']) : null,
                'kelas' => $validated['kelas'] ? trim($validated['kelas']) : null,
            ]
        );

        return redirect()->route('admin.exams.participants', $exam)->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function destroyParticipant(ExamParticipant $participant)
    {
        $examId = $participant->id_exam;
        $participant->delete();

        return redirect()->route('admin.exams.participants', $examId)->with('success', 'Peserta berhasil dihapus dari daftar terdaftar.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Ujian online berhasil dihapus.');
    }

    /** Kelola Soal Ujian */
    public function questions(Exam $exam)
    {
        $questions = $exam->questions;
        return view('admin.exams.questions', compact('exam', 'questions'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'pertanyaan'          => 'required|string',
            'gambar'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'gambar_url'          => 'nullable|string|max:550',
            'jenis'               => 'required|in:pilihan_ganda,essay',
            'pilihan_a'           => 'nullable|required_if:jenis,pilihan_ganda|string',
            'pilihan_b'           => 'nullable|required_if:jenis,pilihan_ganda|string',
            'pilihan_c'           => 'nullable|string',
            'pilihan_d'           => 'nullable|string',
            'pilihan_e'           => 'nullable|string',
            'kunci_jawaban'       => 'nullable|string',
            'kunci_jawaban_essay' => 'nullable|string',
            'bobot_nilai'         => 'required|integer|min:1',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('exams/questions', 'public');
            $validated['gambar'] = '/storage/' . $path;
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar'] = $request->input('gambar_url');
        }
        unset($validated['gambar_url']);

        if ($validated['jenis'] === 'essay' && $request->filled('kunci_jawaban_essay')) {
            $validated['kunci_jawaban'] = $request->input('kunci_jawaban_essay');
        }
        unset($validated['kunci_jawaban_essay']);

        $validated['id_exam'] = $exam->id_exam;
        $validated['urutan']  = $exam->questions()->count() + 1;

        ExamQuestion::create($validated);

        return redirect()->route('admin.exams.questions', $exam)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, ExamQuestion $question)
    {
        $validated = $request->validate([
            'pertanyaan'          => 'required|string',
            'gambar'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'gambar_url'          => 'nullable|string|max:550',
            'hapus_gambar'        => 'nullable|boolean',
            'jenis'               => 'required|in:pilihan_ganda,essay',
            'pilihan_a'           => 'nullable|required_if:jenis,pilihan_ganda|string',
            'pilihan_b'           => 'nullable|required_if:jenis,pilihan_ganda|string',
            'pilihan_c'           => 'nullable|string',
            'pilihan_d'           => 'nullable|string',
            'pilihan_e'           => 'nullable|string',
            'kunci_jawaban'       => 'nullable|string',
            'kunci_jawaban_essay' => 'nullable|string',
            'bobot_nilai'         => 'required|integer|min:1',
        ]);

        if ($request->has('hapus_gambar') && $request->boolean('hapus_gambar')) {
            $validated['gambar'] = null;
        } elseif ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('exams/questions', 'public');
            $validated['gambar'] = '/storage/' . $path;
        } elseif ($request->filled('gambar_url')) {
            $validated['gambar'] = $request->input('gambar_url');
        }
        unset($validated['gambar_url']);
        unset($validated['hapus_gambar']);

        if ($validated['jenis'] === 'essay' && $request->filled('kunci_jawaban_essay')) {
            $validated['kunci_jawaban'] = $request->input('kunci_jawaban_essay');
        }
        unset($validated['kunci_jawaban_essay']);

        $question->update($validated);

        return redirect()->route('admin.exams.questions', $question->id_exam)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroyQuestion(ExamQuestion $question)
    {
        $examId = $question->id_exam;
        $question->delete();

        return redirect()->route('admin.exams.questions', $examId)->with('success', 'Soal berhasil dihapus.');
    }

    /** Unduh Template CSV Import Soal Ujian */
    public function downloadQuestionTemplate(Exam $exam)
    {
        $filename = 'Template_Import_Soal_Ujian.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM untuk MS Excel Windows
            fwrite($file, "sep=;\n");       // Instruksi eksplisit untuk MS Excel agar otomatis membagi ke Kolom A, B, C, D...

            // Header Kolom Rapi per Kolom Excel (A, B, C, D, E, F, G, H, I, J)
            fputcsv($file, [
                'No',
                'Tipe Soal',
                'Pertanyaan',
                'Pilihan A',
                'Pilihan B',
                'Pilihan C',
                'Pilihan D',
                'Pilihan E',
                'Kunci Jawaban',
                'Bobot Nilai',
            ], ';');

            // Baris Contoh Soal 1 (Pilihan Ganda - 5 Opsi)
            fputcsv($file, [
                '1',
                'pilihan_ganda',
                'Berapakah hasil dari 15 + 25?',
                '30',
                '35',
                '40',
                '45',
                '50',
                'C',
                '1',
            ], ';');

            // Baris Contoh Soal 2 (Pilihan Ganda - 4 Opsi)
            fputcsv($file, [
                '2',
                'pilihan_ganda',
                'Ibu kota negara Republik Indonesia adalah...',
                'Surabaya',
                'Jakarta',
                'Bandung',
                'Medan',
                '',
                'B',
                '1',
            ], ';');

            // Baris Contoh Soal 3 (Essay / Uraian)
            fputcsv($file, [
                '3',
                'essay',
                'Jelaskan secara singkat proses fotosintesis pada tumbuhan hijau!',
                '',
                '',
                '',
                '',
                '',
                'Fotosintesis adalah proses pembuatan makanan oleh tumbuhan hijau dengan bantuan sinar matahari dan klorofil.',
                '10',
            ], ';');

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    /** Import Soal dari File CSV / Dokument Spreadsheet */
    public function importQuestionsFile(Request $request, Exam $exam)
    {
        $request->validate([
            'file_soal' => 'required|file|max:10240',
        ], [
            'file_soal.required' => 'Silakan pilih file dokumen soal terlebih dahulu.',
            'file_soal.max'      => 'Ukuran file dokumen melebihi batas maksimal (10 MB).',
        ]);

        $file = $request->file('file_soal');
        $filePath = $file->getRealPath();
        $fileContent = file_get_contents($filePath);

        if (!$fileContent) {
            return back()->with('error', 'File dokumen soal kosong atau tidak dapat dibaca.');
        }

        // Hapus UTF-8 BOM jika ada
        $fileContent = preg_replace('/\x{EF}\x{BB}\x{BF}/u', '', $fileContent);
        $lines = preg_split('/\r\n|\r|\n/', trim($fileContent));

        // Abaikan baris sep= jika ada
        if (count($lines) > 0 && str_starts_with(strtolower(trim($lines[0])), 'sep=')) {
            array_shift($lines);
        }

        if (count($lines) < 2) {
            return back()->with('error', 'File dokumen soal harus berisi header dan minimal 1 baris soal.');
        }

        // Deteksi delimiter (titik koma ';' atau koma ',')
        $firstLine = $lines[0];
        $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        // Read Header
        $headerCols = str_getcsv($firstLine, $delimiter);

        $normalize = function ($str) {
            return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
        };

        // Map header index
        $colMap = [];
        foreach ($headerCols as $idx => $colName) {
            $norm = $normalize($colName);
            $colMap[$norm] = $idx;
        }

        // Helper untuk ambil value berdasarkan alias header
        $getVal = function ($row, $possibleKeys, $default = '') use ($colMap, $normalize) {
            foreach ((array)$possibleKeys as $pk) {
                $normPk = $normalize($pk);
                if (isset($colMap[$normPk]) && isset($row[$colMap[$normPk]])) {
                    return trim($row[$colMap[$normPk]]);
                }
            }
            return $default;
        };

        $importedCount = 0;
        $maxUrutan = $exam->questions()->max('urutan') ?? 0;

        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) continue;

            $row = str_getcsv($line, $delimiter);
            if (empty(array_filter($row))) continue;

            $pertanyaan = $getVal($row, ['pertannyansoal', 'pertanyaan', 'soal', 'question', 'tekssoal']);
            if (!$pertanyaan) {
                // Fallback jika tanpa header baku: gunakan kolom index 2 (pertanyaan)
                if (isset($row[2]) && !empty(trim($row[2]))) {
                    $pertanyaan = trim($row[2]);
                } elseif (isset($row[1]) && !empty(trim($row[1]))) {
                    $pertanyaan = trim($row[1]);
                } else {
                    continue;
                }
            }

            $rawJenis = strtolower($getVal($row, ['tipesoal', 'tipesoalpilihangandaessay', 'jenis', 'tipe', 'type']));
            if (empty($rawJenis) && isset($row[1])) {
                $rawJenis = strtolower(trim($row[1]));
            }
            $jenis = str_contains($rawJenis, 'essay') ? 'essay' : 'pilihan_ganda';

            $pilihanA = $getVal($row, ['pilihan a', 'pilihana', 'a', 'opsi a']);
            $pilihanB = $getVal($row, ['pilihan b', 'pilihanb', 'b', 'opsi b']);
            $pilihanC = $getVal($row, ['pilihan c', 'pilihanc', 'c', 'opsi c']);
            $pilihanD = $getVal($row, ['pilihan d', 'pilihand', 'd', 'opsi d']);
            $pilihanE = $getVal($row, ['pilihan e', 'pilihane', 'e', 'opsi e']);

            // Fallback by position index jika header custom
            if (empty($pilihanA) && isset($row[3])) $pilihanA = trim($row[3]);
            if (empty($pilihanB) && isset($row[4])) $pilihanB = trim($row[4]);
            if (empty($pilihanC) && isset($row[5])) $pilihanC = trim($row[5]);
            if (empty($pilihanD) && isset($row[6])) $pilihanD = trim($row[6]);
            if (empty($pilihanE) && isset($row[7])) $pilihanE = trim($row[7]);

            $kunci = $getVal($row, ['kunci jawaban', 'kuncijawaban', 'kunci', 'key']);
            if (empty($kunci) && isset($row[8])) $kunci = trim($row[8]);
            if ($jenis === 'pilihan_ganda') {
                $kunci = strtoupper($kunci);
            }

            $bobotRaw = $getVal($row, ['bobot nilai', 'bobotnilai', 'bobot', 'skor', 'poin']);
            if (empty($bobotRaw) && isset($row[9])) $bobotRaw = trim($row[9]);
            $bobot = is_numeric($bobotRaw) && (int)$bobotRaw > 0 ? (int)$bobotRaw : 1;

            $maxUrutan++;

            ExamQuestion::create([
                'id_exam'       => $exam->id_exam,
                'pertanyaan'    => $pertanyaan,
                'jenis'         => $jenis,
                'pilihan_a'     => $pilihanA,
                'pilihan_b'     => $pilihanB,
                'pilihan_c'     => $pilihanC,
                'pilihan_d'     => $pilihanD,
                'pilihan_e'     => $pilihanE,
                'kunci_jawaban' => $kunci,
                'bobot_nilai'   => $bobot,
                'urutan'        => $maxUrutan,
            ]);

            $importedCount++;
        }

        if ($importedCount === 0) {
            return back()->with('error', 'Tidak ada soal valid yang berhasil diimpor. Pastikan file berisi data soal yang sesuai.');
        }

        return redirect()->route('admin.exams.questions', $exam)
            ->with('success', "Berhasil mengimpor {$importedCount} soal baru dari dokumen ke dalam bank soal.");
    }

    /** Import Soal dari Copy-Paste Teks Bulk */
    public function importQuestionsText(Request $request, Exam $exam)
    {
        $request->validate([
            'raw_text' => 'required|string',
        ], [
            'raw_text.required' => 'Silakan tempelkan (paste) teks soal pada kolom yang telah disediakan.',
        ]);

        $rawText = $request->input('raw_text');

        // Pisahkan teks menjadi blok-blok soal berdasarkan baris kosong atau nomor soal
        $blocks = preg_split('/\n\s*\n/', trim($rawText));
        $importedCount = 0;
        $maxUrutan = $exam->questions()->max('urutan') ?? 0;

        foreach ($blocks as $block) {
            $lines = array_map('trim', explode("\n", trim($block)));
            $lines = array_values(array_filter($lines));

            if (count($lines) === 0) continue;

            $pertanyaanLines = [];
            $options = ['a' => null, 'b' => null, 'c' => null, 'd' => null, 'e' => null];
            $kunci = null;
            $bobot = 1;
            $jenis = 'pilihan_ganda';

            foreach ($lines as $line) {
                // Cek Opsi Jawaban A. B. C. D. E.
                if (preg_match('/^[A-Ea-e][\.\)]\s*(.+)$/i', $line, $matches)) {
                    $optKey = strtolower(substr($line, 0, 1));
                    $options[$optKey] = trim($matches[1]);
                    continue;
                }

                // Cek Kunci Jawaban
                if (preg_match('/^(?:Kunci|Jawaban|Kunci Jawaban)\s*:\s*(.+)$/i', $line, $matches)) {
                    $kunci = trim($matches[1]);
                    continue;
                }

                // Cek Bobot Nilai
                if (preg_match('/^(?:Bobot|Skor|Poin)\s*:\s*(\d+)/i', $line, $matches)) {
                    $bobot = (int)$matches[1];
                    continue;
                }

                // Baris pertanyaan
                $pertanyaanLines[] = $line;
            }

            if (count($pertanyaanLines) === 0) continue;

            $pertanyaan = implode("\n", $pertanyaanLines);
            // Bersihkan awalan nomor soal jika ada (misal: "1. Berapakah...")
            $pertanyaan = preg_replace('/^\d+[\.\)]\s*/', '', $pertanyaan);

            // Tentukan jenis soal
            if (empty($options['a']) && empty($options['b'])) {
                $jenis = 'essay';
            } else {
                $jenis = 'pilihan_ganda';
                if ($kunci) {
                    $kunci = strtoupper(trim($kunci));
                }
            }

            $maxUrutan++;

            ExamQuestion::create([
                'id_exam'       => $exam->id_exam,
                'pertanyaan'    => $pertanyaan,
                'jenis'         => $jenis,
                'pilihan_a'     => $options['a'],
                'pilihan_b'     => $options['b'],
                'pilihan_c'     => $options['c'],
                'pilihan_d'     => $options['d'],
                'pilihan_e'     => $options['e'],
                'kunci_jawaban' => $kunci,
                'bobot_nilai'   => $bobot,
                'urutan'        => $maxUrutan,
            ]);

            $importedCount++;
        }

        if ($importedCount === 0) {
            return back()->with('error', 'Tidak ada soal valid yang berhasil diimpor dari teks. Pastikan format teks soal sudah sesuai.');
        }

        return redirect()->route('admin.exams.questions', $exam)
            ->with('success', "Berhasil mengimpor {$importedCount} soal baru dari teks bulk.");
    }

    /** Penilaian Manual Jawaban Essay */
    public function updateEssayScore(Request $request, ExamAnswer $answer)
    {
        $validated = $request->validate([
            'nilai_soal' => 'required|numeric|min:0',
        ]);

        $maxBobot = $answer->question ? $answer->question->bobot_nilai : 100;
        $nilai = min($validated['nilai_soal'], $maxBobot);

        $answer->update([
            'nilai_soal' => $nilai,
            'is_benar'   => $nilai > 0,
        ]);

        // Rekalkulasi total skor akhir peserta
        $attempt = $answer->attempt;
        if ($attempt) {
            $exam = $attempt->exam;
            $totalSkorPerolehan = ExamAnswer::where('id_attempt', $attempt->id_attempt)->sum('nilai_soal');
            $totalBobotMaksimal = $exam ? $exam->questions()->sum('bobot_nilai') : 0;

            $skorAkhir = 0;
            if ($totalBobotMaksimal > 0) {
                $skorAkhir = round(($totalSkorPerolehan / $totalBobotMaksimal) * 100, 2);
            }

            $attempt->update(['skor_akhir' => $skorAkhir]);
        }

        return back()->with('success', 'Nilai jawaban essay berhasil disimpan dan skor akhir peserta telah diperbarui.');
    }

    /** Rekap Nilai Ujian */
    public function results(Exam $exam, Request $request)
    {
        $query = $exam->attempts()->where('status', 'selesai');

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peserta', 'LIKE', "%{$search}%")
                  ->orWhere('nis_email', 'LIKE', "%{$search}%");
            });
        }

        $attempts = $query->latest('waktu_selesai')->paginate(20);
        $kelases  = $exam->attempts()->distinct()->pluck('kelas');

        return view('admin.exams.results', compact('exam', 'attempts', 'kelases'));
    }

    public function showResult(ExamAttempt $attempt)
    {
        $attempt->load(['exam', 'answers.question']);
        return view('admin.exams.result_detail', compact('attempt'));
    }

    /** Export CSV Rekapitulasi Nilai & Jawaban */
    public function exportCsv(Exam $exam)
    {
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

            // UTF-8 BOM untuk Microsoft Excel Windows
            fwrite($file, "\xEF\xBB\xBF");

            // Header Informasi Ujian
            fputcsv($file, ['REKAPITULASI HASIL & JAWABAN UJIAN ONLINE']);
            fputcsv($file, ['Judul Ujian', $exam->judul]);
            fputcsv($file, ['Mata Pelajaran', $exam->mata_pelajaran]);
            fputcsv($file, ['Durasi Ujian', $exam->durasi_menit . ' Menit']);
            fputcsv($file, ['Total Soal', $questions->count() . ' Soal']);
            fputcsv($file, ['Tanggal Rekap', date('d-m-Y H:i:s')]);
            fputcsv($file, []); // Baris Kosong

            // Header Tabel Utama
            $tableHeader = [
                'No',
                'Nama Peserta',
                'NIS / Email',
                'Kelas',
                'Waktu Mulai',
                'Waktu Selesai',
                'Durasi (Menit)',
                'Jawaban Benar (PG)',
                'Jawaban Salah (PG)',
                'Skor PG',
                'Skor Essay',
                'Skor Akhir (Nilai 100)',
                'Status Keterangan',
            ];

            // Tambahkan Kolom Detail Per-Soal
            foreach ($questions as $idx => $q) {
                $num = $idx + 1;
                $tableHeader[] = "Soal {$num} (Jwb)";
                $tableHeader[] = "Soal {$num} (Kunci)";
                $tableHeader[] = "Soal {$num} (Nilai)";
            }

            fputcsv($file, $tableHeader);

            // Baris Data Peserta
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

                // Append rincian jawaban per-soal
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

    /** Export Print / PDF Laporan Hasil Ujian */
    public function exportPrint(Exam $exam)
    {
        $attempts = $exam->attempts()
            ->where('status', 'selesai')
            ->latest('waktu_selesai')
            ->get();

        $questionsCount = $exam->questions()->count();
        $setting = \App\Models\Setting::first();

        // Hitung Statistik Nilai Ujian
        $kkm          = $exam->kkm ?? 75;
        $totalPeserta = $attempts->count();
        $avgScore     = $totalPeserta > 0 ? round($attempts->avg('skor_akhir'), 2) : 0;
        $maxScore     = $totalPeserta > 0 ? $attempts->max('skor_akhir') : 0;
        $minScore     = $totalPeserta > 0 ? $attempts->min('skor_akhir') : 0;
        $lulusCount   = $attempts->where('skor_akhir', '>=', $kkm)->count();
        $gagalCount   = $totalPeserta - $lulusCount;

        return view('admin.exams.export_print', compact(
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

    /** Cetak Lembar Jawaban Siswa Individual */
    public function printAttempt(ExamAttempt $attempt)
    {
        $attempt->load(['exam.questions', 'answers.question']);
        $setting = \App\Models\Setting::first();

        return view('admin.exams.attempt_print', compact('attempt', 'setting'));
    }
}
