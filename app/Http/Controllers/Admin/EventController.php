<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class EventController extends Controller
{
    /**
     * Helper function untuk menginisialisasi Cloudinary
     */
    private function cloudinary()
    {
        return new Cloudinary(env('CLOUDINARY_URL'));
    }

    public function index()
    {
        $events = Event::with(['category', 'partner'])->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['partner_id'] = auth()->user()->partner ? auth()->user()->partner->id : null;

        // UPLOAD KE CLOUDINARY
        if ($request->hasFile('poster')) {
            $upload = $this->cloudinary()->uploadApi()->upload(
                $request->file('poster')->getRealPath(),
                ['folder' => 'posters']
            );

            $validated['poster_path'] = $upload['secure_url'];
        }

        unset($validated['poster']);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    public function show(Event $event)
    {
        $categories = Category::all();
        return view('event-detail', compact('categories', 'event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // UPLOAD BARU KE CLOUDINARY
        if ($request->hasFile('poster')) {
            $upload = $this->cloudinary()->uploadApi()->upload(
                $request->file('poster')->getRealPath(),
                ['folder' => 'posters']
            );

            $data['poster_path'] = $upload['secure_url'];
        }

        unset($data['poster']);

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}
