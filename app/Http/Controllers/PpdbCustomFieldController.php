<?php

namespace App\Http\Controllers;

use App\Models\PpdbCustomField;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PpdbCustomFieldController extends Controller
{
    /**
     * Tampilkan daftar field kustom formulir PPDB
     */
    public function index()
    {
        $fields = PpdbCustomField::orderBy('urutan')->orderBy('id')->get();
        $setting = Setting::latest()->first();

        return view('admin.ppdb.fields.index', compact('fields', 'setting'));
    }

    /**
     * Simpan field kustom baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'field_key'   => 'nullable|string|max:100|regex:/^[a-zA-Z0-9_]+$/|unique:ppdb_custom_fields,field_key',
            'tipe'        => 'required|in:text,number,textarea,select,checkbox,date',
            'options_raw' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'help_text'   => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'urutan'      => 'nullable|integer',
            'aktif'       => 'nullable|boolean',
        ]);

        $options = null;
        if (!empty($request->options_raw) && in_array($request->tipe, ['select', 'checkbox'])) {
            $options = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->options_raw)))));
        }

        $fieldKey = !empty($request->field_key)
            ? Str::slug($request->field_key, '_')
            : Str::slug($request->label, '_');

        // Check unique field key manually if auto generated
        $originalKey = $fieldKey;
        $count = 1;
        while (PpdbCustomField::where('field_key', $fieldKey)->exists()) {
            $fieldKey = $originalKey . '_' . $count++;
        }

        PpdbCustomField::create([
            'label'       => $request->label,
            'field_key'   => $fieldKey,
            'tipe'        => $request->tipe,
            'options'     => $options,
            'placeholder' => $request->placeholder,
            'help_text'   => $request->help_text,
            'is_required' => (bool) $request->is_required,
            'urutan'      => (int) ($request->urutan ?? 0),
            'aktif'       => $request->has('aktif') ? (bool) $request->aktif : true,
        ]);

        return redirect()->route('admin.ppdb.fields.index')
            ->with('success', "Field formulir PPDB baru '{$request->label}' berhasil ditambahkan.");
    }

    /**
     * Update field kustom PPDB
     */
    public function update(Request $request, $id)
    {
        $field = PpdbCustomField::findOrFail($id);

        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'tipe'        => 'required|in:text,number,textarea,select,checkbox,date',
            'options_raw' => 'nullable|string',
            'placeholder' => 'nullable|string|max:255',
            'help_text'   => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'urutan'      => 'nullable|integer',
            'aktif'       => 'nullable|boolean',
        ]);

        $options = null;
        if (!empty($request->options_raw) && in_array($request->tipe, ['select', 'checkbox'])) {
            $options = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $request->options_raw)))));
        }

        $field->update([
            'label'       => $request->label,
            'tipe'        => $request->tipe,
            'options'     => $options,
            'placeholder' => $request->placeholder,
            'help_text'   => $request->help_text,
            'is_required' => (bool) $request->is_required,
            'urutan'      => (int) ($request->urutan ?? 0),
            'aktif'       => $request->has('aktif') ? (bool) $request->aktif : false,
        ]);

        return redirect()->route('admin.ppdb.fields.index')
            ->with('success', "Field formulir '{$field->label}' berhasil diperbarui.");
    }

    /**
     * Hapus field kustom
     */
    public function destroy($id)
    {
        $field = PpdbCustomField::findOrFail($id);
        $name = $field->label;
        $field->delete();

        return redirect()->route('admin.ppdb.fields.index')
            ->with('success', "Field formulir '{$name}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif/nonaktif field kustom
     */
    public function toggle($id)
    {
        $field = PpdbCustomField::findOrFail($id);
        $field->aktif = !$field->aktif;
        $field->save();

        $statusStr = $field->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Field formulir '{$field->label}' berhasil {$statusStr}.");
    }
}
