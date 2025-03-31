<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\FilmSessions;
use App\Models\Movies;
use App\Models\Tickets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'lastname' => 'Doe', // Añadido apellido
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'recaptcha_token' => 'fake_recaptcha_token' // Agregar token fake
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'recaptcha_token' => 'fake_recaptcha_token' // Agregar token fake
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrong-password',
            'recaptcha_token' => 'fake_recaptcha_token' // Agregar token fake
        ]);

        $response->assertStatus(422); // Cambiar a 422 si es un error de validación
    }

    public function test_authenticated_user_can_purchase_tickets()
    {
        $user = User::factory()->create();
        $session = FilmSessions::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tickets/purchase', [
            'session_id' => $session->id,
            'seats' => ['A1', 'A2'],
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['ticket']);
    }

    public function test_cannot_purchase_tickets_unauthenticated()
    {
        $response = $this->postJson('/api/tickets/purchase', [
            'session_id' => 1,
            'seats' => ['A1'],
        ]);

        $response->assertStatus(401);
    }

    public function test_can_retrieve_tickets_by_email()
    {
        $user = User::factory()->create();
        $ticket = Tickets::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/tickets?email={$user->email}");

        $response->assertStatus(200)
                 ->assertJsonStructure([['ticket']]);
    }

    public function test_can_list_all_sessions()
    {
        FilmSessions::factory()->count(3)->create();

        $response = $this->getJson('/api/sessions');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_get_single_session()
    {
        $session = FilmSessions::factory()->create();

        $response = $this->getJson("/api/sessions/{$session->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['session']);
    }

    public function test_can_reserve_seats_for_session()
    {
        $user = User::factory()->create();
        $session = FilmSessions::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/sessions/{$session->id}/reserve", [
            'seats' => ['A1', 'A2'],
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['reserved_seats']);
    }

    public function test_can_get_available_seats_for_session()
    {
        $session = FilmSessions::factory()->create();

        $response = $this->getJson("/api/sessions/{$session->id}/seats");

        $response->assertStatus(200)
                 ->assertJsonStructure(['available_seats']);
    }

    public function test_can_list_all_movies()
    {
        Movies::factory()->count(5)->create();

        $response = $this->getJson('/api/movies');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function test_can_get_single_movie()
    {
        $movie = Movies::factory()->create();

        $response = $this->getJson("/api/movies/{$movie->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['movie']);
    }
}
