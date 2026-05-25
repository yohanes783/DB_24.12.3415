<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
   public function index(Request $request)
{
    // Tangkap data keyword pencarian dari URL (?search=...)
    $search = $request->get('search');

    // Modifikasi fungsi Read dengan sintaks Eloquent LIKE
    $partners = Partner::when($search, function ($query, $search) {
        return $query->where('name', 'LIKE', "%{$search}%");
    })->latest()->get();

    // Kirim data $search ke view
    return view('admin.partners.index', compact('partners', 'search'));
}


    // Tambahkan fungsi ini di dalam class PartnerController
        public function create()
    {
    // Mengarah ke file view form tambah partner Anda
    return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required',
        ]);

        Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url,
        ]);

        return redirect('/admin/partners')
            ->with('success', 'Partner berhasil ditambahkan');
    }

    public function edit($id)
{
    $partner = Partner::findOrFail($id);

    return view('admin.partners.edit', compact('partner'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'logo_url' => 'required',
    ]);

    $partner = Partner::findOrFail($id);

    $partner->update([
        'name' => $request->name,
        'logo_url' => $request->logo_url,
    ]);

    return redirect()
        ->route('admin.partners.index')
        ->with('success', 'Partner berhasil diperbarui');
}

    public function destroy($id)
{
    $partner = Partner::findOrFail($id);

    $partner->delete();

    return redirect()
        ->route('admin.partners.index')
        ->with('success', 'Partner berhasil dihapus');
}
}
