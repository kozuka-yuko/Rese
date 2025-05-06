<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendEmailJob;
use Tests\TestCase;
use Database\Factories\RoleFactory;
use App\Models\User;

class MailableControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /** @test */
    public function it_displays_the_email_form()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $response = $this->get('/emails/send_email');

        $response->assertStatus(200);
        $response->assertViewIs('emails.send_email');
    }

    /** @test */
    public function it_fails_validation_when_required_fields_are_missing()
    {
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        $response = $this->post('/emails/send_email', []);
         $response->assertSessionHasErrors(['groupType', 'title', 'body']);
    }

    /** @test */
    public function it_dispatches_email_job_to_general_users()
    {
        $this->withoutExceptionHandling();
        $adminRole = RoleFactory::new()->create(['name' => 'admin']);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin);

        Bus::fake();

        $user = User::factory()->create();

        $data = [
            'groupType' => 'general',
            'title' => 'Test Title',
            'body' => 'Test Body',
        ];

        $response = $this->post('/emails/send_email', $data);

        $response->assertRedirect();
        $response->assertSessionHas('result', 'メールが送信されました');

        Bus::assertDispatched(SendEmailJob::class, function ($job) use ($user) {
            return $job->email === $user->email && $job->details['title'] === 'Test Title';
        });
    }
}
