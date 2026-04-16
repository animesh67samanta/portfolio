<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => Contact::query()->latest()->paginate(20),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): Response
    {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => Contact::query()->latest()->paginate(20),
            'selectedContact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message deleted successfully.');
    }
}
