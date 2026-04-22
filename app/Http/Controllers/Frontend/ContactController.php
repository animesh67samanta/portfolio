<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactRequest $request): RedirectResponse
    {
        Contact::query()->create([
            ...$request->validated(),
            'is_read' => false,
            'status' => 'new',
            'responded_at' => null,
        ]);

        return back()->with('success', 'Thank you. I will contact you very soon.');
    }
}
