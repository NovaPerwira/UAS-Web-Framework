<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AgreementRouteTest extends TestCase
{
    public function test_agreements_index_page_loads()
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $response = $this->actingAs($user)->get('/agreements');

        if ($response->status() !== 200) {
            echo "RESPONSE CODE: " . $response->status() . "\n";
            echo "RESPONSE CONTENT: " . strip_tags($response->getContent()) . "\n";
        }

        $response->assertStatus(200);
    }
}
