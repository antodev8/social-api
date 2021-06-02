<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key',Role::ROLE_ADMIN)->first());

        $user = new User();
        $user->name = 'Post author';
        $user->email = 'pa@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_POST_AUTHOR)->first());

        $user = new User();
        $user->name = 'Guest user';
        $user->email = 'gu@admin.it';
        $user->password = Hash::make('password');
        $user->save();

        $user->roles()->attach(Role::where('key', Role::ROLE_GUEST_USER)->first());

    }
}
