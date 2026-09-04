<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private function checkAdminAccess()
    {
        $user = auth()->user();
        if (!$user || !$user->role || strtolower($user->role->name) !== 'admin') {
            abort(403, 'Akses Ditolak. Fitur Pengaturan Website (Setting) hanya dapat diakses oleh Administrator (Admin).');
        }
    }

    public function index()
    {
        $this->checkAdminAccess();
        $setting = Setting::first();

        return view('admin.settings.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();
        $setting = Setting::first();
        $data = $request->validate([
            'website_name'        => ['nullable', 'string', 'max:255'],
            'website_description' => ['nullable', 'string'],
            'logo'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,ico', 'max:2048'],
            'logo_url'            => ['nullable', 'string', 'max:255'],
            'logo_instansi'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,ico', 'max:2048'],
            'logo_instansi_url'   => ['nullable', 'string', 'max:255'],
            'favicon'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,ico', 'max:2048'],
            'favicon_url'         => ['nullable', 'string', 'max:255'],
            'alamat'              => ['nullable', 'string', 'max:255'],
            'email'               => ['nullable', 'email'],
            'telepon'             => ['nullable', 'string', 'max:255'],
            'facebook'            => ['nullable', 'url'],
            'instagram'           => ['nullable', 'url'],
            'youtube'             => ['nullable', 'url'],
            'linkedin'            => ['nullable', 'url'],
            'footer'              => ['nullable', 'string'],
            'google_maps'         => ['nullable', 'string'],
            'hero_bg'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'hero_bg_url'         => ['nullable', 'string', 'max:550'],
            'hero_bgs.*'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'hero_bgs_urls'       => ['nullable', 'string'],
            'hero_tagline'        => ['nullable', 'string', 'max:255'],
            'hero_title'          => ['nullable', 'string', 'max:255'],
            'hero_subtitle'       => ['nullable', 'string'],
            'hero_btn1_text'      => ['nullable', 'string', 'max:100'],
            'hero_btn1_link'      => ['nullable', 'string', 'max:255'],
            'hero_btn2_text'      => ['nullable', 'string', 'max:100'],
            'hero_btn2_link'      => ['nullable', 'string', 'max:255'],
            'hero_btn3_text'      => ['nullable', 'string', 'max:100'],
            'hero_btn3_link'      => ['nullable', 'string', 'max:255'],
            'tanggal_live'        => ['nullable', 'date'],
            // PPDB & Informasi Pembelajaran
            'ppdb_aktif'          => ['nullable', 'boolean'],
            'ppdb_tahun'          => ['nullable', 'string', 'max:20'],
            'ppdb_keterangan'     => ['nullable', 'string'],
            'ppdb_link_daftar'    => ['nullable', 'url'],
            'ppdb_jurusan'        => ['nullable', 'string'],
            'info_pendaftaran_pembelajaran_judul'    => ['nullable', 'string', 'max:255'],
            'info_pendaftaran_pembelajaran_subjudul' => ['nullable', 'string'],
            'info_pendaftaran_pembelajaran_konten'   => ['nullable', 'string'],
            'info_faq_q.*'                           => ['nullable', 'string'],
            'info_faq_a.*'                           => ['nullable', 'string'],
        ]);

        $data['ppdb_aktif'] = $request->has('ppdb_aktif');

        if ($request->has('info_faq_q') && $request->has('info_faq_a')) {
            $questions = $request->input('info_faq_q', []);
            $answers = $request->input('info_faq_a', []);
            $faqArray = [];
            foreach ($questions as $idx => $q) {
                $qClean = trim($q ?? '');
                $aClean = trim($answers[$idx] ?? '');
                if (!empty($qClean) && !empty($aClean)) {
                    $faqArray[] = [
                        'q' => $qClean,
                        'a' => $aClean,
                    ];
                }
            }
            $data['info_faq_list'] = $faqArray;
        }

        if ($request->filled('google_maps')) {
            $rawMaps = trim($request->input('google_maps'));
            if (preg_match('/https?:\/\/(maps\.app\.goo\.gl|goo\.gl\/maps)\/[A-Za-z0-9_-]+/i', $rawMaps, $matches)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withOptions([
                        'allow_redirects' => false,
                        'verify' => false,
                        'timeout' => 5,
                    ])->get($matches[0]);

                    $redirectUrl = $response->header('Location');
                    if ($redirectUrl) {
                        $rawMaps = $redirectUrl;
                    }
                } catch (\Exception $e) {
                    // Jika jaringan offline/timeout, tetap gunakan URL asli
                }
            }
            $data['google_maps'] = $rawMaps;
        }

        if ($request->filled('ppdb_jurusan')) {
            $rawJurusan = preg_split('/[\r\n,]+/', $request->input('ppdb_jurusan'));
            $jurusanClean = array_values(array_filter(array_map('trim', $rawJurusan)));
            $data['ppdb_jurusan'] = $jurusanClean;
        } else {
            $data['ppdb_jurusan'] = [];
        }

        // Olah multiple hero background images
        $oldHeroBgs = $setting ? $setting->hero_bg_list : [];
        $heroBgsList = [];

        if ($request->has('has_existing_hero_bgs')) {
            $heroBgsList = array_values($request->input('existing_hero_bgs', []));
        } elseif ($request->has('existing_hero_bgs')) {
            $heroBgsList = array_values($request->input('existing_hero_bgs'));
        } elseif ($setting) {
            $heroBgsList = $setting->hero_bg_list;
        }

        if ($request->hasFile('hero_bgs')) {
            foreach ($request->file('hero_bgs') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('settings/hero', 'public');
                    $heroBgsList[] = '/storage/' . $path;
                }
            }
        }

        if ($request->filled('hero_bgs_urls')) {
            $rawUrls = preg_split('/[\r\n,]+/', $request->input('hero_bgs_urls'));
            foreach ($rawUrls as $url) {
                $trimmed = trim($url);
                if (!empty($trimmed) && !in_array($trimmed, $heroBgsList)) {
                    $heroBgsList[] = $trimmed;
                }
            }
        }

        if ($request->hasFile('hero_bg')) {
            $path = $request->file('hero_bg')->store('settings', 'public');
            $singleBg = '/storage/' . $path;
            $data['hero_bg'] = $singleBg;
            if (!in_array($singleBg, $heroBgsList)) {
                array_unshift($heroBgsList, $singleBg);
            }
        } elseif ($request->filled('hero_bg_url')) {
            $singleBg = $request->input('hero_bg_url');
            $data['hero_bg'] = $singleBg;
            if (!in_array($singleBg, $heroBgsList)) {
                array_unshift($heroBgsList, $singleBg);
            }
        }

        $data['hero_bgs'] = array_values(array_unique(array_filter($heroBgsList)));
        if (count($data['hero_bgs']) > 0) {
            $data['hero_bg'] = $data['hero_bgs'][0];
        } else {
            $data['hero_bg'] = null;
        }
        unset($data['hero_bg_url']);
        unset($data['hero_bgs_urls']);

        // Hapus file fisik dari storage disk jika foto di-uncheck / dihapus dari slider
        $removedImages = array_diff($oldHeroBgs, $data['hero_bgs']);
        foreach ($removedImages as $removedImg) {
            if (\Illuminate\Support\Str::startsWith($removedImg, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($removedImg, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
        }

        if ($request->boolean('remove_logo')) {
            if ($setting && $setting->logo && \Illuminate\Support\Str::startsWith($setting->logo, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->logo, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($setting && $setting->logo && \Illuminate\Support\Str::startsWith($setting->logo, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->logo, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $path = $request->file('logo')->store('settings', 'public');
            $data['logo'] = '/storage/' . $path;
        } elseif ($request->filled('logo_url')) {
            $data['logo'] = $request->input('logo_url');
        } else {
            unset($data['logo']);
        }
        unset($data['logo_url'], $data['remove_logo']);

        if ($request->boolean('remove_logo_instansi')) {
            if ($setting && $setting->logo_instansi && \Illuminate\Support\Str::startsWith($setting->logo_instansi, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->logo_instansi, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $data['logo_instansi'] = null;
        } elseif ($request->hasFile('logo_instansi')) {
            if ($setting && $setting->logo_instansi && \Illuminate\Support\Str::startsWith($setting->logo_instansi, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->logo_instansi, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $path = $request->file('logo_instansi')->store('settings', 'public');
            $data['logo_instansi'] = '/storage/' . $path;
        } elseif ($request->filled('logo_instansi_url')) {
            $data['logo_instansi'] = $request->input('logo_instansi_url');
        } else {
            unset($data['logo_instansi']);
        }
        unset($data['logo_instansi_url'], $data['remove_logo_instansi']);

        if ($request->boolean('remove_favicon')) {
            if ($setting && $setting->favicon && \Illuminate\Support\Str::startsWith($setting->favicon, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->favicon, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $data['favicon'] = null;
        } elseif ($request->hasFile('favicon')) {
            if ($setting && $setting->favicon && \Illuminate\Support\Str::startsWith($setting->favicon, '/storage/')) {
                $relativePath = \Illuminate\Support\Str::after($setting->favicon, '/storage/');
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                }
            }
            $path = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = '/storage/' . $path;

            try {
                @copy($request->file('favicon')->getRealPath(), public_path('favicon.ico'));
            } catch (\Exception $e) {
            }
        } elseif ($request->filled('favicon_url')) {
            $data['favicon'] = $request->input('favicon_url');
        } else {
            unset($data['favicon']);
        }
        unset($data['favicon_url'], $data['remove_favicon']);

        $settingObj = Setting::updateOrCreate([], $data);

        ActivityLog::record('update', 'Memperbarui Pengaturan Website & Tanggal Live Sistem', $settingObj);

        return redirect()->route('admin.settings')->with('success', 'Setting website berhasil disimpan.');
    }
}
