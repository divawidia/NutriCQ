<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_page_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_valid_if_minimum_data_is_filled()
    {
        //$user = User::factory()->create([]);

        //$response = $this->post('/register',[array_keys($user)]);
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertValid();
    }

    public function test_is_invalid_without_name()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);
 
        $response->assertInvalid(['name']);
    }

    public function test_is_invalid_with_number_in_name()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu 543',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);
 
        $response->assertInvalid(['name']);
    }

    public function test_is_invalid_without_email()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu 543',
            'email' => '',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);
 
        $response->assertInvalid(['email']);
    }

    public function test_is_invalid_if_email_duplicate()
    {
        $role = Role::create([
            'roles_name' => 'User'
        ]);
        $user = User::create([
                'name' => 'I Putu Fajar Tapa Mahendra',
                'email' => 'ftapamahendra@gmail.com',
                'password' => 'rahasia1234',
                'tgl_lahir' => '2001-08-20',
                'no_telp' => '082146077890',
                'gender' => 'Pria',
                'roles_id' => 1

        ]);

        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa ',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia12',
            'tgl_lahir' => '2001-08-26',
            'no_telp' => '082146071234',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);
        
        $response->assertInvalid(['email']);
    }

    public function test_is_invalid_if_email_is_not_in_proper_form()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu 543',
            'email' => 'Hallo There',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);
 
        $response->assertInvalid(['email']);
    }

    public function test_is_invalid_without_password()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => NULL,
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['password']);
    }

    public function test_is_invalid_if_password_less_than_8_character()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => '1234567',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['password']);
    }

    public function test_is_invalid_if_password_more_than_16_character()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => '12345678901234567',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['password']);
    }

    public function test_is_invalid_without_tgl_lahir()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['tgl_lahir']);
    }

    public function test_is_invalid_if_tgl_lahir_are_not_in_proper_form(){
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['tgl_lahir']);
    }

    public function test_is_invalid_without_no_telp()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => NULL,
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['no_telp']);
    }

    public function test_is_invalid_if_no_telp_are_not_in_number()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => 'hallo',
            'gender' => 'Pria',
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['no_telp']);
    }

    public function test_is_invalid_without_gender()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => NULL,
            'roles_id' => 1,
        ]);

        $response->assertInvalid(['gender']);
    }

    public function test_is_invalid_without_roles_id()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => NULL,
        ]);

        $response->assertInvalid(['roles_id']);
    }

    public function test_is_invalid_if_roles_id_are_not_number()
    {
        $response = $this->post('/register', [
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 'hallo',
        ]);

        $response->assertInvalid(['roles_id']);
    }

    public function test_is_valid_when_role_is_doctor()
    {
        
    }
}
