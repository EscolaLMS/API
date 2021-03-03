<?php

namespace Tests\APIs;

use App\Repositories\Contracts\ConfigRepositoryContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\CreatesUsers;
use Tests\TestCase;

class ShareApiTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function testGetLinkedinUrl(): void
    {
        $configRepository = app(ConfigRepositoryContract::class);
        $user = $this->makeStudent([
        ]);

        $this->response = $this->actingAs($user)->json('GET', '/api/share/linkedin', [
            'url' => 'http://test.com/article123',
        ]);
        $this->response->assertOk();
        $url = $this->response->json()['url'];
        $this->assertStringContainsString('linkedin', $url);
        $this->assertStringContainsString('url=' . urlencode('http://test.com/article123'), $url);
    }

    public function testGetFacebookUrl(): void
    {
        $configRepository = app(ConfigRepositoryContract::class);
        $user = $this->makeStudent([
        ]);

        $this->response = $this->actingAs($user)->json('GET', '/api/share/facebook', [
            'url' => 'http://test.com/article213',
        ]);
        $this->response->assertOk();
        $url = $this->response->json()['url'];
        $this->assertStringContainsString('facebook', $url);
        $this->assertStringContainsString('u=' . urlencode('http://test.com/article213'), $url);
    }

    public function testGetTwitterUrl(): void
    {
        $configRepository = app(ConfigRepositoryContract::class);
        $user = $this->makeStudent([
        ]);

        $this->response = $this->actingAs($user)->json('GET', '/api/share/twitter', [
            'url' => 'http://test.com/article321',
        ]);
        $this->response->assertOk();
        $url = $this->response->json()['url'];
        $this->assertStringContainsString('twitter', $url);
        $this->assertStringContainsString('url=' . urlencode('http://test.com/article321'), $url);
    }
}
