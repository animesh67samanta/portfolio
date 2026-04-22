<?php

namespace App\Services\Admin;

use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    public function list(int $perPage = 20): LengthAwarePaginator
    {
        return Contact::query()->latest()->paginate($perPage);
    }

    public function create(array $data): Contact
    {
        return Contact::query()->create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);

        return $contact;
    }

    public function markAsRead(Contact $contact): Contact
    {
        $contact->update([
            'is_read' => true,
        ]);

        return $contact;
    }

    public function markAsUnread(Contact $contact): Contact
    {
        $contact->update([
            'is_read' => false,
        ]);

        return $contact;
    }

    public function reply(Contact $contact, string $message): void
    {
        Mail::to($contact->email)->send(new ContactReplyMail($contact, $message));

        $contact->update([
            'is_read' => true,
            'status' => 'closed',
            'responded_at' => now(),
        ]);
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}
