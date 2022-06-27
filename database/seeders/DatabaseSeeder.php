<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Food_Diary;
use App\Models\Food_Detail;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Role::create([
            'roles_name' => 'admin'
        ]);

        Role::create([
            'roles_name' => 'dokter'
        ]);

        Role::create([
            'roles_name' => 'user'
        ]);

        User::create([
            'name' => 'I Putu Fajar Tapa Mahendra',
            'email' => 'ftapamahendra@gmail.com',
            'password' => 'rahasia1234',
            'tgl_lahir' => '2001-08-20',
            'no_telp' => '082146077890',
            'gender' => 'Pria',
            'roles_id' => 3,
        ]);

        Food_Diary::create([
            'user_id' => '1',
            'tgl_food_diary' => '2022-07-21',
            'total_air' => 100,
            'total_energi' => 100,
            'total_protein' => 100,
            'total_lemak' => 100,
            'total_karbohidrat' => 100,
            'total_serat' => 100,
            'total_abu' => 100,
            'total_kalsium' => 100,
            'total_fosfor' => 100,
            'total_besi' => 100,
            'total_natrium' => 100,
            'total_kalium' => 100,
            'total_tembaga' => 100,
            'total_seng' => 100,
            'total_retinol' => 100,
            'total_b_karoten' => 100,
            'total_karoten_total' => 100,
            'total_thiamin' => 100,
            'total_riboflamin' => 100,
            'total_niasin' => 100,
            'total_vitamin_c' => 100,
            'jumlah' => 3
        ]);

        Food_Diary::create([
            'user_id' => '1',
            'tgl_food_diary' => '2022-07-21',
            'total_air' => 50,
            'total_energi' => 50,
            'total_protein' => 50,
            'total_lemak' => 50,
            'total_karbohidrat' => 50,
            'total_serat' => 50,
            'total_abu' => 50,
            'total_kalsium' => 50,
            'total_fosfor' => 50,
            'total_besi' => 50,
            'total_natrium' => 50,
            'total_kalium' => 50,
            'total_tembaga' => 50,
            'total_seng' => 50,
            'total_retinol' => 50,
            'total_b_karoten' => 50,
            'total_karoten_total' => 50,
            'total_thiamin' => 50,
            'total_riboflamin' => 50,
            'total_niasin' => 50,
            'total_vitamin_c' => 50,
            'jumlah' => 2
        ]);

        Food_Diary::create([
            'user_id' => '1',
            'tgl_food_diary' => '2022-07-21',
            'total_air' => 25,
            'total_energi' => 25,
            'total_protein' => 25,
            'total_lemak' => 25,
            'total_karbohidrat' => 25,
            'total_serat' => 25,
            'total_abu' => 25,
            'total_kalsium' => 25,
            'total_fosfor' => 25,
            'total_besi' => 25,
            'total_natrium' => 25,
            'total_kalium' => 25,
            'total_tembaga' => 25,
            'total_seng' => 25,
            'total_retinol' => 25,
            'total_b_karoten' => 25,
            'total_karoten_total' => 25,
            'total_thiamin' => 25,
            'total_riboflamin' => 25,
            'total_niasin' => 25,
            'total_vitamin_c' => 25,
            'jumlah' => 1
        ]);

        Food_Detail::create([
            'food_id' => 22,
            'food_diary_id' => 1,
            'takaran_saji' => 100,
        ]);

        Food_Detail::create([
            'food_id' => 176,
            'food_diary_id' => 1,
            'takaran_saji' => 100,
        ]);

        Food_Detail::create([
            'food_id' => 330,
            'food_diary_id' => 1,
            'takaran_saji' => 100,
        ]);

        Food_Detail::create([
            'food_id' => 36,
            'food_diary_id' => 2,
            'takaran_saji' => 100,
        ]);

        Food_Detail::create([
            'food_id' => 150,
            'food_diary_id' => 3,
            'takaran_saji' => 100,
        ]);

        Food_Detail::create([
            'food_id' => 320,
            'food_diary_id' => 2,
            'takaran_saji' => 100,
        ]);
    }
}
