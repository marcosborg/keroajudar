<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBeneficiaryPermissions extends Migration
{
    public function up()
    {
        $permissions = [
            'beneficiary_management_access',
            'beneficiary_category_create',
            'beneficiary_category_edit',
            'beneficiary_category_show',
            'beneficiary_category_delete',
            'beneficiary_category_access',
            'beneficiary_create',
            'beneficiary_edit',
            'beneficiary_show',
            'beneficiary_delete',
            'beneficiary_access',
        ];

        foreach ($permissions as $title) {
            DB::table('permissions')->updateOrInsert(
                ['title' => $title],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $adminRoleId = DB::table('roles')->where('title', 'Admin')->value('id');
        if ($adminRoleId) {
            $permissionIds = DB::table('permissions')
                ->whereIn('title', $permissions)
                ->pluck('id')
                ->all();

            foreach ($permissionIds as $pid) {
                DB::table('permission_role')->updateOrInsert(
                    ['role_id' => $adminRoleId, 'permission_id' => $pid],
                    ['role_id' => $adminRoleId, 'permission_id' => $pid]
                );
            }
        }
    }
}
