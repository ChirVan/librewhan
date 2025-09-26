<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common password for all test users
        $commonPassword = Hash::make('12345678');

        $names = [
            'Chester Dapatnapo', 'Rolando Dela Pena', 'Ivan Bullo', 'Kent Ancheta',
            'Ivan Dela Pena', 'Nicole Pedroso', 'Jhon Bolivar', 'Via Jeves',
            'Trishia Del Norte', 'Agrifino Dacles',
        ];

        $i = 0;
        foreach ($names as $index => $name) {
            // Generate email based on name
            $emailName = strtolower(str_replace(' ', '.', $name));
            $email = $emailName . '@gmail.com';
            
            // Classic Looping, if $i is even = barista, odd = admin
            $i++;
            $usertype = 'barista';
            if($i % 2 == 0){
                $usertype = 'barista';                
            }else{
                $usertype = 'admin';
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'usertype' => $usertype,
                'email_verified_at' => Carbon::now(), // All emails verified for testing
                'password' => $commonPassword,
                'created_at' => Carbon::now()->subDays(rand(1, 30)), // Random creation dates
                'updated_at' => Carbon::now(),
            ]);
        }
        
        // Create a specific admin user
        User::create([
            'name' => 'Admin Account',
            'email' => 'admin@gmail.com',
            'usertype' => 'admin',
            'email_verified_at' => Carbon::now(),
            'password' => $commonPassword,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Create a specific barista user
        User::create([
            'name' => 'Barista Account',
            'email' => 'barista@gmail.com',
            'usertype' => 'barista',
            'email_verified_at' => Carbon::now(),
            'password' => $commonPassword,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info('Created 22 test users with the following credentials:');
        $this->command->info('Password for ALL users: 12345678');
    }
}