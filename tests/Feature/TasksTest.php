<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TasksTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // $response = $this->get('/api/tasks');

        // $response->assertStatus(200);
    
        $taskData = [
            'title' => 'John Doe',
            'description' => 'john@example.com',
            'status' => 'pending',
            'prices' => 3000
        ];

        $response = $this->post('/api/tasks', $taskData);
        $response->assertStatus(Response::HTTP_CREATED);

    
    }
}
