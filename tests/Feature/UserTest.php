<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_user_can_register(): void
    {
        // Passport::actingAs(
        //     User::factory()->create(),
        //     ['*'] // Scopes, you can customize based on your requirements
        // );

        // $user = User::factory()->make();
        // $clientRepository = new ClientRepository();
        // $client = $clientRepository->createPersonalAccessClient(
        //     null,
        //     'Test Personal Access Client',
        //     'http://localhost'
        // );

        // DB::table('oauth_personal_access_clients')->insert([
        //     'client_id' => $client->id,
        //     'created_at' => new DateTime,
        //     'updated_at' => new DateTime,
        // ]);

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
        ];

        $response = $this->post('/api/v1/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User created successfully',
            ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }

    public function test_user_can_login(): void
    {
        //The next code was useful until I included the Setup function.
        //It function help to avoid include the $clientRepository on every test method.

        // $clientRepository = new ClientRepository();
        // $client = $clientRepository->createPersonalAccessClient(
        //     null,
        //     'Test Personal Access Client',
        //     'http://localhost'
        // );

        // DB::table('oauth_personal_access_clients')->insert([
        //     'client_id' => $client->id,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->post('/api/v1/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Logged In successfully',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'access_token',
            ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_logout(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*'] // Scopes, you can customize based on your requirements
        );
        $user = User::factory()->create();
        // $token = $user->createToken('test-token')->accessToken;

        $response = $this->actingAs($user)
            ->get('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged Out Successfully',
            ]);

        $this->assertEmpty($user->tokens);
    }

    public function test_user_can_update()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*'] // Scopes, you can customize based on your requirements
        );
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a user to update
        $userToUpdate = User::factory()->create();

        // Prepare the update data
        $updateData = [
            'name' => 'John Doe',
            'password' => 'newpassword',
            'address' => 'New Address',
            'graduating' => 'New Graduating',
            'email' => 'new@example.com',
            'role' => 'admin',
        ];

        // Send the update request
        $response = $this->put("/api/v1/users/{$userToUpdate->id}", $updateData);

        // Assert response status and content
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User updated successfully',
            ]);

        // Assert that the user was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'John Doe',
            'address' => 'New Address',
            'graduating' => 'New Graduating',
            'email' => 'new@example.com',
            'role' => 'admin',
        ]);
    }

    public function testUserDestroy()
    {
        Passport::actingAs(
            User::factory()->create([]),
            ['*'] // Scopes, you can customize based on your requirements
        );
        // Create a user with the SuperAdmin role
        $user = User::factory()->create(['email' => 'superadmin@test.com','password' => 'newpassword','role' => '1']);

        // Authenticate as the user with SuperAdmin role
        $this->actingAs($user);

        // Create the data for the destroy request
        $destroyData = [
            'email' => 'superadmin@test.com',
            'password' => 'newpassword',
        ];

        // Send the destroy request
        $response = $this->delete("/api/v1/users/{$user->id}", $destroyData);

        // Assert response status and content
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User Deleted successfully',
            ]);
    }
}
