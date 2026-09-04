<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpdbAdminController extends Controller
{
    /**
     * Tampilkan daftar pendaftar PPDB
     */
    public function index(Request $request)
    {
        $query = PpdbRegistration::query();

        // Search by name, registration no, NISN, phone
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_lengkap', 'LIKE', "%{$q}%")
                    ->orWhere('no_pendaftaran', 'LIKE', "%{$q}%")
                    ->orWhere('nisn', 'LIKE', "%{$q}%")
                    ->orWhere('no_hp', 'LIKE', "%{$q}%")
                    ->orWhere('sekolah_asal', 'LIKE', "%{$q}%");
            });
        }

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'diterima', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        $pendaftar = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total'    => PpdbRegistration::count(),
            'pending'  => PpdbRegistration::where('status', 'pending')->count(),
            'diterima' => PpdbRegistration::where('status', 'diterima')->count(),
            'ditolak'  => PpdbRegistration::where('status', 'ditolak')->count(),
        ];

        $setting = Setting::latest()->first();

        return view('admin.ppdb.index', compact('pendaftar', 'stats', 'setting'));
    }

    /**
     * Form pendaftaran PPDB manual / offline oleh Admin
     */
    public function create()
    {
        $setting = Setting::latest()->first();
        if (!$setting || !$setting->ppdb_aktif) {
            return redirect()->route('admin.ppdb.index')->with('error', 'Pendaftaran PPDB saat ini sedang ditutup di Pengaturan Website. Aktifkan PPDB di menu Setting terlebih dahulu untuk menginputkan pendaftar offline.');
        }

        $customFields = \App\Models\PpdbCustomField::activeOrdered()->get();

        return view('admin.ppdb.create', compact('setting', 'customFields'));
    }

    /**
     * Simpan pendaftaran PPDB manual / offline oleh Admin
     */
    public function store(Request $request)
    {
        $setting = Setting::latest()->first();
        if (!$setting || !$setting->ppdb_aktif) {
            return redirect()->route('admin.ppdb.index')->with('error', 'Pendaftaran PPDB saat ini sedang ditutup di Pengaturan Website. Aktifkan PPDB di menu Setting terlebih dahulu untuk menginputkan pendaftar offline.');
        }

        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nisn'           => 'nullable|string|max:30',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'agama'          => 'required|string|max:50',
            'alamat'         => 'required|string',
            'sekolah_asal'   => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:30',
            'email'          => 'nullable|email|max:255',
            'jurusan'        => 'nullable|string|max:100',
            'berkas'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status'         => 'required|in:pending,diterima,ditolak',
            'catatan'        => 'nullable|string|max:500',
        ]);

        $berkasPath = null;
        if ($request->hasFile('berkas')) {
            $berkasPath = $request->file('berkas')->store('ppdb_berkas', 'public');
        }

        $validated['no_pendaftaran'] = PpdbRegistration::generateNoPendaftaran();
        $validated['berkas'] = $berkasPath;
        $validated['data_tambahan'] = $request->input('data_tambahan', []);

        $registration = PpdbRegistration::create($validated);

        return redirect()->route('admin.ppdb.index')->with('success', "Data pendaftar offline {$registration->nama_lengkap} (No. Pendaftaran: {$registration->no_pendaftaran}) berhasil ditambahkan.");
    }

    /**
     * Cetak rekap seluruh pendaftar PPDB (dengan filter jika ada)
     */
    public function printAll(Request $request)
    {
        $query = PpdbRegistration::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_lengkap', 'LIKE', "%{$q}%")
                    ->orWhere('no_pendaftaran', 'LIKE', "%{$q}%")
                    ->orWhere('nisn', 'LIKE', "%{$q}%")
                    ->orWhere('no_hp', 'LIKE', "%{$q}%")
                    ->orWhere('sekolah_asal', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'diterima', 'ditolak'])) {
            $query->where('status', $request->status);
        }

        $pendaftar = $query->latest('id')->get();
        $setting = Setting::latest()->first();
        $statusFilter = $request->status ?? 'semua';

        return view('admin.ppdb.print_all', compact('pendaftar', 'setting', 'statusFilter'));
    }

    /**
     * Cetak biodata pendaftar tunggal (perorangan)
     */
    public function printSingle($id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);
        $setting = Setting::latest()->first();

        return view('admin.ppdb.print_single', compact('pendaftar', 'setting'));
    }

    /**
     * Detail pendaftar PPDB
     */
    public function show($id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);
        return response()->json($pendaftar);
    }

    /**
     * Update status pendaftaran (Pending / Diterima / Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:pending,diterima,ditolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pendaftar = PpdbRegistration::findOrFail($id);
        $pendaftar->status = $request->status;
        if ($request->has('catatan')) {
            $pendaftar->catatan = $request->catatan;
        }
        $pendaftar->save();

        return redirect()->back()->with('success', "Status pendaftaran No. {$pendaftar->no_pendaftaran} berhasil diperbarui menjadi " . strtoupper($pendaftar->status) . ".");
    }

    /**
     * Hapus pendaftar
     */
    public function destroy($id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);

        if ($pendaftar->berkas) {
            Storage::disk('public')->delete($pendaftar->berkas);
        }

        $pendaftar->delete();

        return redirect()->back()->with('success', 'Data pendaftar PPDB berhasil dihapus.');
    }
}
