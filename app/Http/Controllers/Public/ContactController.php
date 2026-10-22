<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function store(ContactRequest $r)
    {
        ContactMessage::create([...$r->safe()->except('website'), 'ip_hash' => hash_hmac('sha256', (string) $r->ip(), config('app.key'))]);

        return back()->with('success', 'Pesan Anda sudah diterima. Saya akan segera menghubungi Anda.');
    }
}
