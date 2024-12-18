<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
class DataStreamProcessorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_analyze_endpoint_returns_correct_frequencies()
    {
        $payload = [
            'stream' => 'AAABBBCCCAAABBBCCC',
            'k' => 3,
            'top' => 5,
            'exclude' => ['AAA']
        ];
        $response = $this->postJson('/api/data-stream/analyze', $payload);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        ['subsequence' => 'AAB', 'count' => 2],
                        ['subsequence' => 'ABB', 'count' => 2]
                    ]
                ]);
    }

    public function test_handles_large_input()
    {
        $longStream = str_repeat('ABCDEFGH', 125000); // 1 million characters
        $payload = [
            'stream' => $longStream,
            'k' => 3,
            'top' => 5
        ];
        $response = $this->postJson('/api/data-stream/analyze', $payload);
        $response->assertStatus(200);
    }

    public function test_validates_input()
    {
        $response = $this->postJson('/api/data-stream/analyze', [
            'stream' => '',
            'k' => 0,
            'top' => -1
        ]);

        $response->assertStatus(422);
    }
}
