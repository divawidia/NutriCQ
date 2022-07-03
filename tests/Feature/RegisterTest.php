<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_user_able_to_register()
    {
        $password = 'jack1234';

        $this->postJson(route('register.user'), [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'password' => $password,
            'password_confirmation' => $password])
            ->assertValid()
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com']);
    }
}
