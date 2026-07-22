<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Concerns\ActsAsAdmin;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use ActsAsAdmin, RefreshDatabase;

    private function contact(): Contact
    {
        $contact = new Contact();
        $contact->save();

        return $contact;
    }

    public function test_guests_cannot_reach_the_contact_screen(): void
    {
        $this->get('/contacts')->assertRedirect('/login');
    }

    public function test_guests_cannot_update_contact_details(): void
    {
        $contact = $this->contact();

        $this->put('/contacts/' . $contact->id, ['email' => 'x@example.test'])
            ->assertRedirect('/login');
    }

    public function test_show_returns_the_contact_row(): void
    {
        $contact = $this->contact();
        $contact->update(['email' => 'info@example.test']);

        $this->actingAs($this->admin())
            ->getJson('/contacts/' . $contact->id)
            ->assertOk()
            ->assertJsonPath('data.0.email', 'info@example.test');
    }

    public function test_update_saves_the_details(): void
    {
        $contact = $this->contact();

        $this->actingAs($this->admin())
            ->put('/contacts/' . $contact->id, [
                'email'   => 'info@example.test',
                'phone1'  => '123456',
                'address' => 'Somewhere',
            ])
            ->assertRedirect();

        $contact->refresh();

        $this->assertSame('info@example.test', $contact->email);
        $this->assertSame('123456', $contact->phone1);
        $this->assertSame('Somewhere', $contact->address);
    }

    public function test_update_rejects_an_invalid_email(): void
    {
        $contact = $this->contact();

        $this->actingAs($this->admin())
            ->put('/contacts/' . $contact->id, ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');

        $this->assertNull($contact->refresh()->email);
    }

    public function test_update_invalidates_the_shared_contact_cache(): void
    {
        $contact = $this->contact();
        Cache::put('contact', 'stale', now()->addHour());

        $this->actingAs($this->admin())
            ->put('/contacts/' . $contact->id, ['email' => 'info@example.test']);

        $this->assertNull(Cache::get('contact'));
    }
}
