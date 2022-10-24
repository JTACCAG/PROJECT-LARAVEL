<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    // public function test_login_form()
    // {
    //     $this->assertTrue(true);
    // }
    
    public function test_login_form()
    {
        $response = $this->post('/api/login');

        $response->assertStatus(302);
    }
}
