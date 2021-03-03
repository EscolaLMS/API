<?php

namespace Tests\APIs;

use App\Events\PasswordForgotten;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\ApiTestTrait;
use Tests\CreatesUsers;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function testRegister(): void
    {
        Notification::fake();

        $this->response = $this->json('POST', '/api/auth/register', [
            'email' => 'test@test.test',
            'first_name' => 'tester',
            'last_name' => 'tester',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        $this->assertApiSuccess();
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.test',
            'first_name' => 'tester',
            'last_name' => 'tester',
        ]);

        Notification::assertSentTo(User::where('email', 'test@test.test')->first(), VerifyEmail::class);
    }

    public function testLogin(): void
    {
        $this->makeStudent([
            'email' => 'test@test.test',
            'password' => Hash::make('testtest'),
            'email_verified_at' => Carbon::now(),
        ]);

        $this->response = $this->json('POST', '/api/auth/login', [
            'email' => 'test@test.test',
            'password' => 'testtest',
        ]);

        $this->assertApiSuccess();

        $response = $this->response->json();
        $this->assertTrue((bool)strlen($response['token']));
    }

    public function testCantLoginWithoutEmailVerified(): void
    {
        $this->makeStudent([
            'email' => 'test@test.test',
            'password' => Hash::make('testtest'),
            'email_verified_at' => null,
        ]);

        $this->response = $this->json('POST', '/api/auth/login', [
            'email' => 'test@test.test',
            'password' => 'testtest',
        ]);

        $this->response->assertStatus(422);
    }

    public function testCantLoginWithInvalidCredentials(): void
    {
        $this->makeStudent(['email' => 'test@test.test', 'password' => Hash::make('testtest')]);

        $this->response = $this->json('POST', '/api/auth/login', [
            'email' => 'test@test.test',
            'password' => 'test',
        ]);

        $this->response->assertStatus(422);

        $this->response = $this->json('POST', '/api/auth/login', [
            'email' => 'test@te.test',
            'password' => 'testtest',
        ]);

        $this->response->assertStatus(422);
    }

    public function testLogout(): void
    {
        $user = $this->makeStudent();
        Passport::actingAs($user);
        $token = $user->createToken(config('passport.personal_access_client.secret'))->accessToken;
        $this->response = $this->json('POST', '/api/auth/logout', [], [
            'Authorization' => "Bearer $token",
        ]);
        $this->assertApiSuccess();
    }

    public function testForgotPassword(): void
    {
        Event::fake();

        $user = $this->makeStudent();

        $this->response = $this->json('POST', '/api/auth/password/forgot', [
            'email' => $user->email,
            'return_url' => 'http://localhost/password-forgot',
        ]);

        $this->assertApiSuccess();
        Event::assertDispatched(PasswordForgotten::class);
    }

    public function testForgotPasswordWithoutUser(): void
    {
        Event::fake();

        $this->response = $this->json('POST', '/api/auth/password/forgot', [
            'email' => 'not-valid-email@example.com',
            'return_url' => 'http://localhost/password-forgot',
        ]);

        $this->response->assertStatus(422);
        Event::assertNotDispatched(PasswordForgotten::class);
    }

    public function testResetPassword(): void
    {
        $user = $this->makeStudent([
            'password_reset_token' => 'test',
        ]);

        $this->response = $this->json('POST', '/api/auth/password/reset', [
            'email' => $user->email,
            'token' => 'test',
            'password' => 'zaq1@WSX',
        ]);

        $this->assertApiSuccess();
        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'password_reset_token' => null,
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check('zaq1@WSX', $user->password));
    }

    public function testRefreshToken(): void
    {
        $user = $this->makeStudent();
        $this->response = $this->actingAs($user)->json('GET', '/api/auth/refresh');
        $this->assertApiSuccess();

        $response = $this->response->json();
        $this->assertTrue((bool)strlen($response['token']));
    }

    public function testResendEmailVerification(): void
    {
        Notification::fake();

        $user = $this->makeStudent();
        $this->response = $this->json('POST', '/api/auth/email/resend', [
            'email' => $user->email,
        ]);
        $this->assertApiSuccess();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

}
