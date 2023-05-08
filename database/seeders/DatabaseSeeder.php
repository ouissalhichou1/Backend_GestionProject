<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Factories\ProjectFactory;
use Database\Factories\StudentFactory;
use Database\Factories\ProfessorFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
   {   

    //Roles
    DB::table('roles')->insert([
        'RoleName' => 'Admin',
    ]);
    DB::table('roles')->insert([
        'RoleName' => 'Ens',
    ]);
    DB::table('roles')->insert([
        'RoleName' => 'Etu',
    ]);
    
    //Admin Count
    DB::table('users')->insert([
        'name' => 'Admin',
        'surname' => 'Admin',
        'password' => Hash::make('Admin'),
        'email' => 'Admin@uae.ac.ma',
        'email_verified_at' => Carbon::now(),
        'email_verification_token'=>Str::random(40),
    ]);
    // etu account
    DB::table('users')->insert([
        'name' => 'etudiant',
        'surname' => 'etudiant',
        'password' => Hash::make('123456789'),
        'apogee' =>  '11111111',
        'filiere' => 'smi',
        'email' => 'etu@etu.uae.ac.ma',
        'email_verified_at' => Carbon::now(),
        'email_verification_token'=>Str::random(40),
    ]);
    // prof account
    DB::table('users')->insert([
        'name' => 'professor',
        'surname' => 'professor',
        'password' => Hash::make('123456789'),
        'code' =>  '22222222',
        'specialite' => 'info',
        'email' => 'Ens@etu.uae.ac.ma',
        'email_verified_at' => Carbon::now(),
        'email_verification_token'=>Str::random(40),
    ]);
    //Professors
    ProfessorFactory::new()->count(5)->create();
    // students
    StudentFactory::new()->count(5)->create();
} 

}
