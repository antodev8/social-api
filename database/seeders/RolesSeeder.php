<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = "Admin";
        $role->description = "Admin";
        $role->key = Role::ROLE_ADMIN;
        $role->save();

        $role = new Role();
        $role->name = "Post author";
        $role->description = "Post author";
        $role->key = Role::ROLE_POST_AUTHOR;
        $role->save();

        $role = new Role();
        $role->name = "Guest user";
        $role->description = "Guest user";
        $role->key = Role::ROLE_GUEST_USER;
        $role->save();
    }
}
