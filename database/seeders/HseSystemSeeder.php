<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\ReportAccidentDetail;
use App\Models\ReportUnsafeDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HseSystemSeeder extends Seeder
{
    public function run(): void
    {
        $hseAdmin = User::create([
            'name'     => 'HSE Officer',
            'email'    => 'hse@pamitra.co.id',
            'password' => Hash::make('password'),
            'role'     => 'hse',
        ]);

        $manager = User::create([
            'name'     => 'HSE Manager (Superadmin)',
            'email'    => 'manager@pamitra.co.id',
            'password' => Hash::make('password'),
            'role'     => 'manager',
        ]);

        $pic = User::create([
            'name'     => 'PIC Lapangan (Produksi)',
            'email'    => 'pic@pamitra.co.id',
            'password' => Hash::make('password'),
            'role'     => 'pic',
        ]);

    }
}