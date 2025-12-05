<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class SyncPermissionsAndAssignUserSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['id' => 1, 'title' => 'user_management_access'],
            ['id' => 2, 'title' => 'permission_create'],
            ['id' => 3, 'title' => 'permission_edit'],
            ['id' => 4, 'title' => 'permission_show'],
            ['id' => 5, 'title' => 'permission_delete'],
            ['id' => 6, 'title' => 'permission_access'],
            ['id' => 7, 'title' => 'role_create'],
            ['id' => 8, 'title' => 'role_edit'],
            ['id' => 9, 'title' => 'role_show'],
            ['id' => 10, 'title' => 'role_delete'],
            ['id' => 11, 'title' => 'role_access'],
            ['id' => 12, 'title' => 'user_create'],
            ['id' => 13, 'title' => 'user_edit'],
            ['id' => 14, 'title' => 'user_show'],
            ['id' => 15, 'title' => 'user_delete'],
            ['id' => 16, 'title' => 'user_access'],
            ['id' => 17, 'title' => 'entry_create'],
            ['id' => 18, 'title' => 'entry_edit'],
            ['id' => 19, 'title' => 'entry_show'],
            ['id' => 20, 'title' => 'entry_delete'],
            ['id' => 21, 'title' => 'entry_access'],
            ['id' => 22, 'title' => 'country_create'],
            ['id' => 23, 'title' => 'country_edit'],
            ['id' => 24, 'title' => 'country_show'],
            ['id' => 25, 'title' => 'country_delete'],
            ['id' => 26, 'title' => 'country_access'],
            ['id' => 27, 'title' => 'prize_create'],
            ['id' => 28, 'title' => 'prize_edit'],
            ['id' => 29, 'title' => 'prize_show'],
            ['id' => 30, 'title' => 'prize_delete'],
            ['id' => 31, 'title' => 'prize_access'],
            ['id' => 32, 'title' => 'winner_create'],
            ['id' => 33, 'title' => 'winner_edit'],
            ['id' => 34, 'title' => 'winner_show'],
            ['id' => 35, 'title' => 'winner_delete'],
            ['id' => 36, 'title' => 'winner_access'],
            ['id' => 37, 'title' => 'payment_create'],
            ['id' => 38, 'title' => 'payment_edit'],
            ['id' => 39, 'title' => 'payment_show'],
            ['id' => 40, 'title' => 'payment_delete'],
            ['id' => 41, 'title' => 'payment_access'],
            ['id' => 42, 'title' => 'content_management_access'],
            ['id' => 43, 'title' => 'content_category_create'],
            ['id' => 44, 'title' => 'content_category_edit'],
            ['id' => 45, 'title' => 'content_category_show'],
            ['id' => 46, 'title' => 'content_category_delete'],
            ['id' => 47, 'title' => 'content_category_access'],
            ['id' => 48, 'title' => 'content_tag_create'],
            ['id' => 49, 'title' => 'content_tag_edit'],
            ['id' => 50, 'title' => 'content_tag_show'],
            ['id' => 51, 'title' => 'content_tag_delete'],
            ['id' => 52, 'title' => 'content_tag_access'],
            ['id' => 53, 'title' => 'content_page_create'],
            ['id' => 54, 'title' => 'content_page_edit'],
            ['id' => 55, 'title' => 'content_page_show'],
            ['id' => 56, 'title' => 'content_page_delete'],
            ['id' => 57, 'title' => 'content_page_access'],
            ['id' => 58, 'title' => 'profile_password_edit'],
            ['id' => 59, 'title' => 'beneficiary_management_access'],
            ['id' => 60, 'title' => 'beneficiary_category_create'],
            ['id' => 61, 'title' => 'beneficiary_category_edit'],
            ['id' => 62, 'title' => 'beneficiary_category_show'],
            ['id' => 63, 'title' => 'beneficiary_category_delete'],
            ['id' => 64, 'title' => 'beneficiary_category_access'],
            ['id' => 65, 'title' => 'beneficiary_create'],
            ['id' => 66, 'title' => 'beneficiary_edit'],
            ['id' => 67, 'title' => 'beneficiary_show'],
            ['id' => 68, 'title' => 'beneficiary_delete'],
            ['id' => 69, 'title' => 'beneficiary_access'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['id' => $perm['id']], ['title' => $perm['title']]);
        }

        $allIds = Permission::pluck('id')->toArray();

        $adminRole = Role::firstOrCreate(['title' => 'Admin']);
        $adminRole->permissions()->sync($allIds);

        $user = User::firstOrCreate(
            ['email' => 'marcosborges@netlook.pt'],
            ['name' => 'Marcos Borges', 'password' => bcrypt('password')]
        );
        $user->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
