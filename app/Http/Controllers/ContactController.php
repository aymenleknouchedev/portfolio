<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($validated);

        $contactEmail = Setting::get('contact_email');
        if ($contactEmail) {
            try {
                Mail::to($contactEmail)->send(new ContactFormMail($validated));
            } catch (\Throwable $e) {
                Log::error('Contact form email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
