<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactReplyRequest;
use App\Http\Requests\Admin\ContactRequest;
use App\Models\Contact;
use App\Services\Admin\ContactService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContactService $contactService): Response
    {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contactService->list(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request, ContactService $contactService): RedirectResponse
    {
        $contactService->create($request->validated());

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact, ContactService $contactService): Response
    {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contactService->list(),
            'selectedContact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact, ContactService $contactService): RedirectResponse
    {
        $contactService->update($contact, $request->validated());

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message updated successfully.');
    }

    public function markAsRead(Contact $contact, ContactService $contactService): RedirectResponse
    {
        $contactService->markAsRead($contact);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact marked as read.');
    }

    public function markAsUnread(Contact $contact, ContactService $contactService): RedirectResponse
    {
        $contactService->markAsUnread($contact);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact marked as unread.');
    }

    public function reply(ContactReplyRequest $request, Contact $contact, ContactService $contactService): RedirectResponse
    {
        $contactService->reply($contact, $request->validated('message'));

        return redirect()->route('admin.contacts.index')->with('success', 'Reply sent successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact, ContactService $contactService): RedirectResponse
    {
        $contactService->delete($contact);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message deleted successfully.');
    }
}
