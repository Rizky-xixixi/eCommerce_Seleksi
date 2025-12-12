<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus admin jika sudah ada
        Admin::query()->delete();

        // Buat admin baru
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('print123'),
            'name' => 'Administrator',
            'email' => 'dashboard@print.com',
        ]);

        $this->command->info('Admin user created!');
        $this->command->info('Username: admin');
        $this->command->info('Password: print123');
    }
}