<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminMarcosSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'marcosborges@netlook.pt';
        $adminRole = Role::where('title', 'Admin')->first();

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'Marcos Borges',
                'password' => bcrypt('password'),
            ]
        );

        if ($adminRole) {
            $user->roles()->sync([$adminRole->id]);
        }
    }
}
