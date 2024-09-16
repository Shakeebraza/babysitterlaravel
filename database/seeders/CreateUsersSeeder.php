<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
               'first_name'=>'Admin User',
               "surname"=>"adm",
               'email'=>'admin@itsolutionstuff.com',
               'role'=>1,
               'password'=> bcrypt('123456'),
            ],
            [
               'first_name'=>'Manager User',
               "surname"=>"adm",
               'email'=>'manager@itsolutionstuff.com',
               'role'=> 2,
               'password'=> bcrypt('123456'),
            ],
            [
               'first_name'=>'User',
               "surname"=>"adm",
               'email'=>'user@itsolutionstuff.com',
               'role'=>0,
               'password'=> bcrypt('123456'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}