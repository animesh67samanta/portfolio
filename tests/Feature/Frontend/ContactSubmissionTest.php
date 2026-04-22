<?php

use App\Models\Contact;

use function Pest\Laravel\from;
use function Pest\Laravel\post;

test('guests can submit a contact message', function () {
    $response = post(route('contacts.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '1234567890',
        'subject' => 'Website project',
        'message' => 'I would like to discuss a portfolio redesign.',
    ]);

    $response
        ->assertRedirect()
        ->assertSessionHas('success', 'Thank you. I will contact you very soon.');

    $contact = Contact::query()->first();

    expect($contact)
        ->not->toBeNull()
        ->name->toBe('Jane Doe')
        ->email->toBe('jane@example.com')
        ->status->toBe('new')
        ->is_read->toBeFalse();
});

test('contact submission validates required fields', function () {
    $response = from(route('home'))->post(route('contacts.store'), [
        'name' => '',
        'email' => 'not-an-email',
        'message' => '',
    ]);

    $response
        ->assertRedirect(route('home'))
        ->assertSessionHasErrors(['name', 'email', 'message']);

    expect(Contact::query()->count())->toBe(0);
});
