<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PickerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Picker 2',
                'username' => 'picker',
                'password' => Hash::make('password'),
                'role' => 'PICKER'
            ],
            [
                'name' => 'Picker 3',
                'username' => 'picker3',
                'password' => Hash::make('password'),
                'role' => 'PICKER'
            ],
            [
                'name' => 'Suparman',
                'username' => 'driver1',
                'password' => Hash::make('password'),
                'role' => 'DRIVER'
            ],
            [
                'name' => 'Suparman 2',
                'username' => 'driver2',
                'password' => Hash::make('password'),
                'role' => 'DRIVER'
            ]
        ]);
    }
}
