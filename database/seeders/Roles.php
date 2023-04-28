<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'RoleName' => 'Admin',
        ]);
        DB::table('roles')->insert([
            'RoleName' => 'Ens',
        ]);
        DB::table('roles')->insert([
            'RoleName' => 'Etu',
        ]);
    }
}
