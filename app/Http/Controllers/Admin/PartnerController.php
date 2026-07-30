<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Tampilkan daftar Partner (Lengkap dengan filter pencarian & filter status)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $partners = Partner::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.partners.index', compact('partners', 'search', 'status'));
    }

    /**
     * Setujui pendaftaran Partner (Approve)
     */
    public function approve(Partner $partner)
    {
        // 1. Ubah status pengajuan partner menjadi approved
        $partner->update([
            'status' => 'approved'
        ]);

        // 2. Ubah role user terkait menjadi 'partner'
        if ($partner->user) {
            $partner->user->update([
                'role' => 'partner'
            ]);
        }

        return redirect()->back()->with('success', 'Partner berhasil disetujui dan role akun telah diperbarui!');
    }

    /**
     * Tolak pendaftaran Partner (Reject)
     */
    public function reject(Partner $partner)
    {
        // Ubah status menjadi 'rejected' (atau 'ditolak')
        $partner->update([
            'status' => 'rejected'
        ]);

        // Pastikan role user tetap 'user'
        if ($partner->user) {
            $partner->user->update([
                'role' => 'user'
            ]);
        }

        return redirect()->back()->with('success', "Pendaftaran partner '{$partner->name}' telah ditolak.");
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners', 'public');
        }

        Partner::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'logo'        => $logoPath,
            'status'      => 'approved',
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,approved,rejected,ditolak',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status'      => $request->status,
        ];

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        // Jika status di-update manual menjadi approved via form edit, ubah juga role user-nya
        if ($request->status === 'approved' && $partner->user) {
            $partner->user->update(['role' => 'partner']);
        } elseif (in_array($request->status, ['rejected', 'ditolak']) && $partner->user) {
            $partner->user->update(['role' => 'user']);
        }

        return redirect()->route('admin.partners.index')
            ->with('success', 'Data partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        // Hapus logo dari storage jika ada
        if ($partner->logo && Storage::disk('public')->exists($partner->logo)) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus.');
    }
}
