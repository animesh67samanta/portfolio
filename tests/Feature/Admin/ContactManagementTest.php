<?php

use App\Mail\ContactReplyMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

test('admin can manage contacts and send reply', function () {
    Mail::fake();

    /** @var User $admin */
    $admin = User::factory()->admin()->createOne();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->post(route('admin.contacts.store', absolute: false), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'subject' => 'Need help',
            'message' => 'Please contact me.',
            'is_read' => false,
            'status' => 'new',
        ])
        ->assertRedirect(route('admin.contacts.index', absolute: false));

    $contact = Contact::query()->firstOrFail();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->patch(route('admin.contacts.read', ['contact' => $contact], absolute: false))
        ->assertRedirect(route('admin.contacts.index', absolute: false));

    $contact->refresh();
    expect($contact->is_read)->toBeTrue();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->patch(route('admin.contacts.unread', ['contact' => $contact], absolute: false))
        ->assertRedirect(route('admin.contacts.index', absolute: false));

    $contact->refresh();
    expect($contact->is_read)->toBeFalse();

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->post(route('admin.contacts.reply', ['contact' => $contact], absolute: false), [
            'message' => 'Thanks for reaching out. We will reply shortly.',
        ])
        ->assertRedirect(route('admin.contacts.index', absolute: false));

    Mail::assertSent(ContactReplyMail::class);

    $contact->refresh();
    expect($contact->responded_at)->not->toBeNull()
        ->and($contact->status)->toBe('closed');

    /** @var TestCase $this */
    $this->actingAs($admin)
        ->delete(route('admin.contacts.destroy', ['contact' => $contact], absolute: false))
        ->assertRedirect(route('admin.contacts.index', absolute: false));

    expect(Contact::query()->count())->toBe(0);
});
