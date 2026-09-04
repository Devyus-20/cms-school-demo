@extends('admin.layouts.app')

@section('title', 'Pengaturan Website & PPDB')
@section('page-title', 'Pengaturan Website & PPDB')

@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 leading-tight break-words">Pengaturan Website & PPDB</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola informasi profil website sekolah dan pendaftaran siswa baru (PPDB).</p>
    </div>

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Tanggal Live Sistem --}}
        <div class="bg-white rounded-[5px] border border-blue-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-2 h-full bg-blue-500"></div>
            <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-4 pb-3 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Pengaturan Tanggal Live Sistem (Go-Live Date)</span>
                </div>
                @if(isset($setting->tanggal_live) && $setting->tanggal_live)
                    @if($setting->tanggal_live->isFuture())
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            Akan Live: {{ $setting->tanggal_live->translatedFormat('d M Y H:i') }}
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Sistem Telah LIVE ({{ $setting->tanggal_live->translatedFormat('d M Y H:i') }})
                        </span>
                    @endif
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                        Belum Diatur
                    </span>
                @endif
            </h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal & Waktu Peluncuran / Go-Live Sistem</label>
                    <input type="datetime-local" name="tanggal_live"
                           value="{{ old('tanggal_live', isset($setting->tanggal_live) && $setting->tanggal_live ? $setting->tanggal_live->format('Y-m-d\TH:i') : '') }}"
                           class="w-full sm:w-80 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 text-sm text-slate-800 outline-none transition-all">
                    @error('tanggal_live')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pengaturan ini menentukan kapan website/sistem resmi diluncurkan secara publik. Anda dapat menyesuaikan tanggal ini sewaktu-waktu sesuai jadwal go-live sekolah.
                </p>
            </div>
        </div>

        {{-- Pengaturan PPDB --}}
        <div class="bg-white rounded-[5px] border border-emerald-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-2 h-full bg-emerald-500"></div>
            <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-5 pb-3 border-b border-emerald-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                Penerimaan Peserta Didik Baru (PPDB)
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                    <input type="checkbox" name="ppdb_aktif" value="1" id="ppdb_aktif" {{ old('ppdb_aktif', $setting->ppdb_aktif ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-emerald-300 focus:ring-emerald-500">
                    <label for="ppdb_aktif" class="text-sm font-semibold text-emerald-900 cursor-pointer">Buka Pendaftaran PPDB di Website</label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Ajaran PPDB</label>
                        <input name="ppdb_tahun" value="{{ old('ppdb_tahun', $setting->ppdb_tahun ?? '2026/2027') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                               placeholder="2026/2027">
                        @error('ppdb_tahun')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Link Formulir Pendaftaran (Google Form / Portal)</label>
                        <input name="ppdb_link_daftar" type="url" value="{{ old('ppdb_link_daftar', $setting->ppdb_link_daftar ?? '') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                               placeholder="https://forms.google.com/...">
                        @error('ppdb_link_daftar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan / Alur & Syarat PPDB</label>
                    <textarea name="ppdb_keterangan" rows="4"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-y"
                              placeholder="Persyaratan pendaftaran, gelombang, kontak panitia PPDB, dll...">{{ old('ppdb_keterangan', $setting->ppdb_keterangan ?? '') }}</textarea>
                    @error('ppdb_keterangan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Daftar Jurusan / Program Studi Sekolah</label>
                    <input name="ppdb_jurusan" value="{{ old('ppdb_jurusan', isset($setting) ? implode(', ', $setting->jurusan_list) : 'MIPA, IPS, Keagamaan') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Contoh: MIPA, IPS, Keagamaan, Teknik Komputer & Jaringan">
                    <p class="mt-1.5 text-xs text-slate-500">Pisahkan setiap jurusan dengan tanda koma (<strong>,</strong>). Jurusan ini akan otomatis menjadi pilihan pada formulir pendaftaran PPDB Online & Cetak Offline (PDF).</p>
                    @error('ppdb_jurusan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Pengaturan Informasi Seputar Pendaftaran & Pembelajaran --}}
        <div class="bg-white rounded-[5px] border border-indigo-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
            <h3 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-5 pb-3 border-b border-indigo-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Informasi Seputar Pendaftaran & Pembelajaran (Halaman Depan)</span>
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Seksi Informasi</label>
                    <input name="info_pendaftaran_pembelajaran_judul"
                           value="{{ old('info_pendaftaran_pembelajaran_judul', $setting->info_pendaftaran_pembelajaran_judul ?? 'Informasi Seputar Pendaftaran & Pembelajaran') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Informasi Seputar Pendaftaran & Pembelajaran">
                    @error('info_pendaftaran_pembelajaran_judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subjudul / Ringkasan Singkat</label>
                    <textarea name="info_pendaftaran_pembelajaran_subjudul" rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 text-sm text-slate-800 outline-none transition-all"
                              placeholder="Panduan pendaftaran siswa baru PPDB, alur verifikasi, serta sistem pembelajaran digital CBT di sekolah.">{{ old('info_pendaftaran_pembelajaran_subjudul', $setting->info_pendaftaran_pembelajaran_subjudul ?? 'Panduan alur pendaftaran siswa baru PPDB, syarat berkas, serta sistem pembelajaran digital terpadu di sekolah kami.') }}</textarea>
                    @error('info_pendaftaran_pembelajaran_subjudul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Informasi Lengkap Pendaftaran & Pembelajaran</label>
                    <textarea name="info_pendaftaran_pembelajaran_konten" rows="5"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 text-sm text-slate-800 outline-none transition-all resize-y"
                              placeholder="Tuliskan detail mengenai alur pendaftaran, jadwal seleksi, kurikulum pembelajaran, dan panduan penggunaan portal siswa CBT...">{{ old('info_pendaftaran_pembelajaran_konten', $setting->info_pendaftaran_pembelajaran_konten ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Informasi ini akan ditampilkan pada Seksi Khusus di Halaman Depan Website dan Halaman PPDB Publik.</p>
                    @error('info_pendaftaran_pembelajaran_konten')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Kelola Kartu Pertanyaan & Jawaban FAQ (Sebelah Kanan) --}}
                <div class="pt-4 border-t border-indigo-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-indigo-900 uppercase tracking-wider">
                            ❓ Kelola Kartu Pertanyaan & Jawaban FAQ (Sebelah Kanan)
                        </label>
                        <span class="text-xs text-slate-500">Kartu tanya-jawab interaktif pada halaman depan.</span>
                    </div>

                    @php
                        $faqList = old('info_faq_q', isset($setting) && is_array($setting->info_faq_list) && count($setting->info_faq_list) > 0 ? $setting->info_faq_list : [
                            [
                                'q' => 'Bagaimana cara mendaftar peserta didik baru (PPDB) di MA Al Ikhlas?',
                                'a' => 'Pendaftaran PPDB dapat dilakukan secara online 24 jam melalui portal website ini (/ppdb) atau secara offline di Sekretariat Panitia PPDB sekolah. Syarat pendaftaran meliputi: 1) Fotokopi Ijazah/SKL SMP/MTs, 2) Akta Kelahiran & Kartu Keluarga (KK), 3) Pas foto 3x4 (3 lembar), dan 4) Mengisi formulir pendaftaran secara lengkap. Nomor pendaftaran resmi akan terbit otomatis setelah formulir berhasil dikirim.'
                            ],
                            [
                                'q' => 'Apakah tersedia program beasiswa untuk calon siswa?',
                                'a' => 'Ya, MA Al Ikhlas menyediakan 3 program beasiswa utama: 1) Beasiswa Prestasi Akademik bagi peringkat 1-3 sekolah asal/kejuaraan OSN, 2) Beasiswa Hafidz Al-Qur\'an (Bebas Biaya Pendidikan untuk minimal 3 Juz), dan 3) Beasiswa Bantuan Pendidikan PIP/KKS/PKH bagi calon siswa kurang mampu yang berprestasi.'
                            ],
                            [
                                'q' => 'Bagaimana sistem Ujian Online (CBT) di MA Al Ikhlas?',
                                'a' => 'Sistem Ujian Online CBT (Computer Based Test) MA Al Ikhlas terintegrasi dengan Portal Akun Siswa Digital. Siswa dapat mengikuti Ujian Harian (UH), UTS, UAS, dan Asesmen Madrasah dari perangkat laptop/smartphone secara praktis dengan proteksi kecurangan digital, alokasi waktu otomatis, dan rekapitulasi nilai yang transparan.'
                            ],
                            [
                                'q' => 'Apa saja kegiatan ekstrakurikuler unggulan yang tersedia?',
                                'a' => 'Ekstrakurikuler unggulan meliputi: 1) Keagamaan: Tahfidz Al-Qur\'an, Hadroh/Seni Qasidah, & Kajian Kitab Kuning; 2) Kepemimpinan: Pramuka Bantara, Paskibra, PMR, & OPMIS; 3) Olahraga: Futsal, Basket, Voli, & Pencak Silat; 4) Teknologi & Seni: Jurnalistik Digital, Multimedia Podcast, Karya Ilmiah Remaja (KIR), & English Club.'
                            ]
                        ]);
                    @endphp

                    <div id="faqContainer" class="space-y-4">
                        @foreach($faqList as $index => $item)
                            @php
                                $question = is_array($item) ? ($item['q'] ?? '') : $item;
                                $answer = is_array($item) ? ($item['a'] ?? '') : (old('info_faq_a.'.$index) ?? '');
                            @endphp
                            <div class="faq-item p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 relative">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-indigo-900 uppercase">Kartu Pertanyaan #<span class="faq-num">{{ $index + 1 }}</span></span>
                                    <button type="button" onclick="removeFaqItem(this)" class="text-xs text-red-500 font-bold hover:underline cursor-pointer">Hapus</button>
                                </div>
                                <input type="text" name="info_faq_q[]" value="{{ $question }}" placeholder="Pertanyaan FAQ..."
                                       class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-800 outline-none focus:border-indigo-400">
                                <textarea name="info_faq_a[]" rows="3" placeholder="Jawaban informasi lengkap dan akurat..."
                                          class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 outline-none focus:border-indigo-400 resize-y">{{ $answer }}</textarea>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addFaqItem()" class="px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all border border-indigo-200 flex items-center gap-1.5 cursor-pointer">
                        <span>+ Tambah Kartu FAQ Baru</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Informasi Dasar --}}
        <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-5 pb-3 border-b border-slate-100">Informasi Dasar Website</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Website</label>
                    <input name="website_name" value="{{ old('website_name', $setting->website_name ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Nama Website Sekolah">
                    @error('website_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                    <input name="website_description" value="{{ old('website_description', $setting->website_description ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Deskripsi singkat website">
                    @error('website_description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload Logo Sekolah Utama (Kop Kiri)</label>
                    @if(isset($setting->logo) && $setting->logo)
                        <div class="mb-2 flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-slate-50">
                            <div class="flex items-center gap-2 min-w-0">
                                <img src="{{ $setting->logo }}" class="w-10 h-10 rounded-lg object-contain border border-slate-200 bg-white p-1 shrink-0">
                                <span class="text-xs text-slate-600 truncate max-w-[160px] sm:max-w-xs">{{ $setting->logo }}</span>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 font-semibold cursor-pointer ml-2 shrink-0 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200 transition-colors">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                Hapus Logo
                            </label>
                        </div>
                    @else
                        <div class="mb-2 flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-slate-50">
                            <img src="{{ asset('images/default-logo.png') }}" class="w-10 h-10 rounded-lg object-contain border border-slate-200 bg-white p-1 shrink-0">
                            <span class="text-xs text-slate-400 italic">Default sistem (Y-School) aktif</span>
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Atau URL logo: <input name="logo_url" value="{{ old('logo_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('logo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload Logo Kementerian / Yayasan (Kop Kanan)</label>
                    @if(isset($setting->logo_instansi) && $setting->logo_instansi)
                        <div class="mb-2 flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-slate-50">
                            <div class="flex items-center gap-2 min-w-0">
                                <img src="{{ $setting->logo_instansi }}" class="w-10 h-10 rounded-lg object-contain border border-slate-200 bg-white p-1 shrink-0">
                                <span class="text-xs text-slate-600 truncate max-w-[160px] sm:max-w-xs">{{ $setting->logo_instansi }}</span>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 font-semibold cursor-pointer ml-2 shrink-0 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200 transition-colors">
                                <input type="checkbox" name="remove_logo_instansi" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                Hapus Logo
                            </label>
                        </div>
                    @endif
                    <input type="file" name="logo_instansi" accept="image/*"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Atau URL logo instansi: <input name="logo_instansi_url" value="{{ old('logo_instansi_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('logo_instansi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload Favicon Browser</label>
                    @if(isset($setting->favicon) && $setting->favicon)
                        <div class="mb-2 flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-slate-50">
                            <div class="flex items-center gap-2 min-w-0">
                                <img src="{{ $setting->favicon }}" class="w-8 h-8 rounded-lg object-contain border border-slate-200 bg-white p-1 shrink-0">
                                <span class="text-xs text-slate-600 truncate max-w-[160px] sm:max-w-xs">{{ $setting->favicon }}</span>
                            </div>
                            <label class="flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 font-semibold cursor-pointer ml-2 shrink-0 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200 transition-colors">
                                <input type="checkbox" name="remove_favicon" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                Hapus Favicon
                            </label>
                        </div>
                    @else
                        <div class="mb-2 flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-slate-50">
                            <img src="{{ asset('images/default-logo.png') }}" class="w-8 h-8 rounded-lg object-contain border border-slate-200 bg-white p-1 shrink-0">
                            <span class="text-xs text-slate-400 italic">Default sistem (Y-School) aktif</span>
                        </div>
                    @endif
                    <input type="file" name="favicon" accept="image/*,.ico"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Atau URL favicon: <input name="favicon_url" value="{{ old('favicon_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('favicon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2 pt-4 border-t border-slate-200">
                    <div class="bg-emerald-50/60 p-4 rounded-xl border border-emerald-200 mb-4">
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Pengaturan Hero Banner & Slider Beranda
                        </h4>
                        <p class="text-xs text-emerald-700">Sesuaikan teks, tombol, dan gambar background slide yang tampil pada banner utama beranda sekolah.</p>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Badge Teks Kecil (Hero Tagline)</label>
                    <input name="hero_tagline" value="{{ old('hero_tagline', $setting->hero_tagline ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Kosongkan untuk otomatis: {{ ($setting->website_name ?? 'MA Al Ikhlas') }} Digital Campus">
                    <p class="mt-1 text-xs text-slate-500">Teks kecil di atas judul utama (Contoh: <strong>MA AL IKHLAS DIGITAL CAMPUS</strong>).</p>
                    @error('hero_tagline')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Slogan Utama Banner (Hero Title)</label>
                    <input name="hero_title" value="{{ old('hero_title', $setting->hero_title ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Contoh: Membangun Generasi Berakhlak Mulia & Berprestasi">
                    <p class="mt-1 text-xs text-slate-500">Judul utama berukuran besar yang muncul pada banner hijau beranda.</p>
                    @error('hero_title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi / Sub-Slogan Banner (Hero Subtitle)</label>
                    <textarea name="hero_subtitle" rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-y"
                              placeholder="Contoh: Pendidikan berkualitas tinggi dengan dasar keagamaan kokoh, fasilitas modern, serta pembinaan minat bakat secara optimal.">{{ old('hero_subtitle', $setting->hero_subtitle ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Teks penjelas di bawah judul slogan utama pada banner beranda.</p>
                    @error('hero_subtitle')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>



                {{-- Multi Photo Upload untuk Hero Slider --}}
                <div class="sm:col-span-2 pt-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Upload Foto Background Banner (Bisa Lebih dari 1 untuk Slider Foto)
                    </label>

                    @php
                        $heroBgsList = isset($setting) ? $setting->hero_bg_list : [];
                    @endphp

                    @if(count($heroBgsList) > 0)
                        <input type="hidden" name="has_existing_hero_bgs" value="1">
                        <div class="mb-3">
                            <span class="text-xs font-semibold text-slate-600 mb-2 block">Daftar Foto Slider Aktif Saat Ini:</span>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($heroBgsList as $idx => $img)
                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-sm">
                                        <img src="{{ $img }}" class="w-full h-24 object-cover">
                                        <div class="p-1.5 bg-slate-900/90 text-white flex items-center justify-between text-[11px]">
                                            <span class="truncate max-w-[100px] font-mono">Slide #{{ $idx + 1 }}</span>
                                            <label class="flex items-center gap-1 cursor-pointer text-emerald-400 hover:text-emerald-300">
                                                <input type="checkbox" name="existing_hero_bgs[]" value="{{ $img }}" checked class="w-3.5 h-3.5 text-emerald-600 rounded">
                                                <span>Simpan</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-[11px] text-slate-500">Uncheck foto yang ingin dihapus dari slide.</p>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-medium text-slate-600 mb-1">Pilih / Tambah Foto Baru (Multiple Upload):</span>
                            <input type="file" name="hero_bgs[]" accept="image/*" multiple
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                            <p class="mt-1 text-[11px] text-slate-400">Anda dapat memilih beberapa file gambar sekaligus. Ukuran gambar akan menyesuaikan container banner beranda secara otomatis.</p>
                        </div>

                        <div>
                            <span class="block text-xs font-medium text-slate-600 mb-1">Atau Masukkan URL Gambar (Pisahkan dengan baris baru atau koma):</span>
                            <textarea name="hero_bgs_urls" rows="2"
                                      class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-800 font-mono outline-none"
                                      placeholder="https://images.unsplash.com/...&#10;https://images.unsplash.com/..."></textarea>
                        </div>
                    </div>
                    @error('hero_bgs')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Kontak & Lokasi Google Maps --}}
        <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-5 pb-3 border-b border-slate-100">Informasi Kontak & Peta Lokasi (Google Maps)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <input name="alamat" value="{{ old('alamat', $setting->alamat ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="Jl. Contoh No. 1, Kota">
                    @error('alamat')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input name="email" type="email" value="{{ old('email', $setting->email ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="info@sekolah.ac.id">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon / WhatsApp</label>
                    <input name="telepon" value="{{ old('telepon', $setting->telepon ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="+62...">
                    @error('telepon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Link Embed Google Maps / Titik Koordinat Sekolah</label>
                    <textarea name="google_maps" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-xs text-slate-800 font-mono outline-none transition-all resize-y"
                              placeholder="Masukkan link iframe dari Google Maps (misal: https://www.google.com/maps/embed?pb=... atau kode <iframe src=...>)">{{ old('google_maps', $setting->google_maps ?? '') }}</textarea>
                    <p class="mt-1.5 text-xs text-slate-500">Buka Google Maps &rarr; Cari Lokasi Sekolah &rarr; Klik Bagikan &rarr; Sematkan Peta (Embed Map) &rarr; Salin URL/kode iframe ke kolom ini.</p>
                    @error('google_maps')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Sosial Media --}}
        <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-5 pb-3 border-b border-slate-100">Sosial Media</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Facebook</label>
                    <input name="facebook" type="url" value="{{ old('facebook', $setting->facebook ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="https://facebook.com/...">
                    @error('facebook')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instagram</label>
                    <input name="instagram" type="url" value="{{ old('instagram', $setting->instagram ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="https://instagram.com/...">
                    @error('instagram')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">YouTube</label>
                    <input name="youtube" type="url" value="{{ old('youtube', $setting->youtube ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="https://youtube.com/...">
                    @error('youtube')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">LinkedIn</label>
                    <input name="linkedin" type="url" value="{{ old('linkedin', $setting->linkedin ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                           placeholder="https://linkedin.com/...">
                    @error('linkedin')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 pb-3 border-b border-slate-100">Teks Footer</h3>
            <textarea name="footer" rows="3"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-none"
                      placeholder="© 2026 MA Al Ikhlas. All rights reserved.">{{ old('footer', $setting->footer ?? '') }}</textarea>
            @error('footer')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
function addFaqItem() {
    var container = document.getElementById('faqContainer');
    var count = container.querySelectorAll('.faq-item').length + 1;
    var div = document.createElement('div');
    div.className = 'faq-item p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2 relative';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-indigo-900 uppercase">Kartu Pertanyaan #<span class="faq-num">${count}</span></span>
            <button type="button" onclick="removeFaqItem(this)" class="text-xs text-red-500 font-bold hover:underline cursor-pointer">Hapus</button>
        </div>
        <input type="text" name="info_faq_q[]" value="" placeholder="Pertanyaan FAQ..."
               class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-800 outline-none focus:border-indigo-400">
        <textarea name="info_faq_a[]" rows="3" placeholder="Jawaban informasi lengkap dan akurat..."
                  class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 outline-none focus:border-indigo-400 resize-y"></textarea>
    `;
    container.appendChild(div);
    reindexFaq();
}

function removeFaqItem(btn) {
    var item = btn.closest('.faq-item');
    if (item) {
        item.remove();
        reindexFaq();
    }
}

function reindexFaq() {
    var items = document.querySelectorAll('.faq-item');
    items.forEach(function(el, idx) {
        var num = el.querySelector('.faq-num');
        if (num) num.innerText = idx + 1;
    });
}
</script>
@endsection
