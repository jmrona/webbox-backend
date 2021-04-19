<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dateNow = Carbon::now();

        DB::table('users')->insert([
            'fullname' => 'admin',
            'biography' => 'Web developer',
            'dob' => '1991/05/02',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'created_at' => $dateNow,
            'updated_at' => $dateNow,
        ]);
    }
}
