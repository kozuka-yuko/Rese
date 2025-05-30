<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     /** @test */
    public function test_example()
    {
        $response = $this->get('/shop');

        $response->assertStatus(200);

        $response = $this->get('/no_route');

        $response->assertStatus(404);
    }
}
