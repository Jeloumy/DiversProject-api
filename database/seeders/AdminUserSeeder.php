<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use app\Http\Controllers\AuthController;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'John',
            'last_name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password',
            'pseudo' => 'adminuser2',
            'admin' => true,
        ]);

    }
}
