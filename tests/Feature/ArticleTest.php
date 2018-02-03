<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    public function testsArticlesAreCreatedCorrectly(){
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = ['title'=>'Lorem', 'body'=>'Ipsum',];
        $this->json('POST', '/api/articles', $payload, $headers)
             ->assertStatus(200)
             ->assertJson(['id'=>1, 'title'=>'Lorem', 'body'=>'Ipsum']);
    }
    public function testsArticlesAreUpdatedCorrectly(){
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $article = factory(Article::class)->create(['title'=>'First article', 'body'=>'First body',]);
        $payload = ['title'=>'Lorem', 'body'=>'Ipsum',];
        $response = $this->json('PUT', '/api/articles' . $article->id, $payload, $headers)
                    ->assertStatus(200)
                    ->assertJson(['id'=>1, 'title'=>'Lorem', 'body'=>'Ipsum']);
    }
    public function testArticlesAreListedCorrectly(){
        factory(Article::class)->create(['title'=>'First article', 'body'=>'First body',]);
        factory(Article::class)->create(['title'=>'Second article', 'body'=>'Second body',]);
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $response = $this->json('GET', '/api/articles', [], $headers)->assertStatus(200)
        ->assertJson([['title'=>'First article', 'body'=>'First body'], ['title'=>'Second article', 'body'=>'Second body']])
        ->assertJsonStructure(['*'=>['id', 'body', 'title', 'created_at', 'updated_at']]);
    }
}
