<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create(["email"=>"admin@gmail.com","is_admin"=>true]);

        $manager =$this->CreateRole('admin');

        $this->CreatePermission(User::$manageUser, $manager);
        $this->CreatePermission(User::$manageAd, $manager);
        $this->CreatePermission(User::$manageCategory, $manager);
        $this->CreatePermission(User::$manageSetting, $manager);

        $user->assignRole($manager);

        \App\Models\User::factory(10)->create();
    }


    /**
     * @return Role
     */
    public function CreateRole($name)
    {
        $ClientRole = new Role();
        $ClientRole->Name = $name;
        $ClientRole->guard_name = 'backpack';
        $ClientRole->save();
        return $ClientRole;
    }

    /**
     * @param $roleName
     * @param $ClientRole
     */
    public function CreatePermission($roleName, $ClientRole)
    {
        $p1 = new Permission();
        $p1->Name = $roleName;
        $p1->guard_name = 'backpack';
        $p1->save();
        $p1->roles()->save($ClientRole);
    }
}
