<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        $this->user = User::factory()->create(['password' => Hash::make('halo1234')]);
        $this->user->roles()->attach($role);
    }

    public function test_user_able_to_login()
    {
        $response = $this->postJson(route('login.user'),[
            'email' => $this->user->email,
            'password' => 'halo1234'])
            ->assertValid()
            ->assertOk();
        
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_if_user_login_incorrect_password_return_error()
    {
        $response = $this->postJson(route('login.user'),[
            'email' => $this->user->email,
            'password' => 'post1234'])
            ->assertUnauthorized();
    }

    public function test_user_not_able_login_if_status_inactive()
    {
        $role2 = Role::factory()->create(['name' => 'doctor', 'display_name' => 'Doctor', 'description' => 'Doctor']);

        $password = 'jack1234';
        $cv = UploadedFile::fake()->create('cv.pdf');
        $lincense = UploadedFile::fake()->create('lincense.pdf');

        //create doctor account
        $this->postJson(route('register.doctor'), [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'no_telp' => '081234567890',
            'cv' => $cv,
            'license' => $lincense])
            ->assertValid()
            ->assertCreated();
        
        $response = $this->postJson(route('login.user'),[
            'email' => 'jackalive@gmail.com',
            'password' => 'jack1234'])
            ->assertUnauthorized();
    }
}
