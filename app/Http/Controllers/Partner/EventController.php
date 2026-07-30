<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Tampilkan daftar event milik partner yang sedang login
     */
    public function index()
    {
        $partner = auth()->user()->partner;

        if (!$partner || $partner->status !== 'approved') {
            return redirect()->route('partner.pending')->with('error', 'Akun partner kamu belum aktif atau belum disetujui.');
        }

        $events = Event::where('partner_id', $partner->id)->latest()->paginate(10);

        return view('partner.events.index', compact('events'));
    }

    /**
     * Form tambah event baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('partner.events.create', compact('categories'));
    }

    /**
     * Simpan event baru ke database
     */
    public function store(Request $request)
    {
        $partner = auth()->user()->partner;

        if (!$partner) {
            return redirect()->back()->with('error', 'Profil partner tidak ditemukan.');
        }

        // Validasi input
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'description' => 'required|string',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Gabungkan jam menjadi format standar (cth: "08:00 - 12:00 WIB")
        $validated['time'] = $request->start_time . ' - ' . $request->end_time . ' WIB';
        unset($validated['start_time'], $validated['end_time']);

        // Tambahkan Partner ID dan buat SLUG otomatis dari Title
        $validated['partner_id'] = $partner->id;
        $validated['slug']       = Str::slug($request->title) . '-' . Str::random(5);

        // Handle upload poster
        if ($request->hasFile('poster')) {
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($validated['poster']);

        Event::create($validated);

        return redirect()->route('partner.events.index')->with('success', 'Event berhasil dibuat dan dipublikasikan!');
    }

    /**
     * Form edit event
     */
    public function edit(Event $event)
    {
        $partner = auth()->user()->partner;

        // Proteksi: Pastikan event milik partner yang sedang login
        if (!$partner || $event->partner_id !== $partner->id) {
            abort(403, 'Akses ditolak.');
        }

        $categories = Category::all();
        return view('partner.events.edit', compact('event', 'categories'));
    }

    /**
     * Update data event
     */
    public function update(Request $request, Event $event)
    {
        $partner = auth()->user()->partner;

        if (!$partner || $event->partner_id !== $partner->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'description' => 'required|string',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Gabungkan jam menjadi format standar (cth: "08:00 - 12:00 WIB")
        $validated['time'] = $request->start_time . ' - ' . $request->end_time . ' WIB';
        unset($validated['start_time'], $validated['end_time']);

        // Perbarui slug jika judul berubah
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(5);

        if ($request->hasFile('poster')) {
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($validated['poster']);

        $event->update($validated);

        return redirect()->route('partner.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Hapus event
     */
    public function destroy(Event $event)
    {
        $partner = auth()->user()->partner;

        if (!$partner || $event->partner_id !== $partner->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('partner.events.index')->with('success', 'Event berhasil dihapus!');
    }
}