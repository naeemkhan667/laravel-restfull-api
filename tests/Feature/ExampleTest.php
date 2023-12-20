<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_posts(){
        Post::factory(3)->create();
        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $this->assertCount(3, $response['data']);
    }

    public function test_can_create_a_post(){
        $post = ['title' => 'title', 'content' => 'content'];
        $response = $this->post('/api/posts', $post);
        $response->assertStatus(200);
        $response->assertSeeText('post successfully created');
        $response->assertJson([
            'status' => true,
            'message' => 'post successfully created',
        ]);
        $this->assertDatabaseHas('posts', $post);

    }

    public function test_can_update_a_post(){
        $post = Post::factory()->create();
        $updatePost = [
            'title' => 'updated title',
            'content' => 'updated contents'
        ];

        $response = $this->put("/api/posts/{$post->id}", $updatePost);
        $response->assertStatus(200);
        $response->assertJson([
            "status" => true,
            "message" => "Post successfully updated"
        ]);
        $this->assertDatabaseHas('posts', $updatePost);
    }

    public function test_can_delete_a_post(){
        $post = Post::factory()->create();
        $response = $this->delete("/api/posts/{$post->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);

    }
}
