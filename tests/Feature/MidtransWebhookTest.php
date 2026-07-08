<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_callback_accepts_post_without_csrf_and_returns_ok(): void
    {
        $response = $this->postJson('/midtrans/callback', []);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'OK']);
    }
}
