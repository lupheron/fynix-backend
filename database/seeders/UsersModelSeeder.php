<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            'fullname' => "Nuriddinov Suxrob Adxamovich",
            'phone' => "(50) 883 99 11",
            'email' => "nuriddinovsuxrob27@gmail.com",
            'role' => "boshliq",
            'password' => "123"
        ]);
    }
}
