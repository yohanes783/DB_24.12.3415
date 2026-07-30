<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $event = Event::findOrFail($eventId);

        // Ambil partner_id dari event. Jika event tidak punya partner_id, beri null/default.
        $partnerId = $event->partner_id ?? null;

        Review::create([
            'user_id'    => Auth::id(),
            'event_id'   => $event->id,
            'partner_id' => $partnerId, // <-- KUNCI FIX ERROR
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan kamu berhasil dikirim.');
    }
}
