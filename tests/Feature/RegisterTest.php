<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    use WithFaker;

    public function test_user_able_to_register()
    {
        $role = Role::factory()->create(['name' => 'user', 'display_name' => 'User', 'description' => 'User']);
        //dd($role->id);
        $password = 'jack1234';
        $response = $this->postJson(route('register.user'), [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'no_telp' => '081234567890'])
            ->assertValid()
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'no_telp' => '081234567890']);

        $response->assertJsonFragment(['roles' => 'user']);
    }

    public function test_doctor_able_to_register()
    {
        $role = Role::factory()->create(['name' => 'doctor', 'display_name' => 'Doctor', 'description' => 'Doctor']);
        //dd($role->id);
        $password = 'jack1234';
        $cv = UploadedFile::fake()->create('cv.pdf');
        $lincense = UploadedFile::fake()->create('lincense.pdf');
        $response = $this->postJson(route('register.doctor'), [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'no_telp' => '081234567890',
            'cv' => $cv,
            'license' => $lincense])
            ->assertValid()
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'name' => 'Jack',
            'email' => 'jackalive@gmail.com',
            'no_telp' => '081234567890']);

        $response->assertJsonFragment(['roles' => 'doctor']);
    }
}
