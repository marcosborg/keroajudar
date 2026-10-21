<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            'ad_campaign_create',
            'ad_campaign_edit',
            'ad_campaign_show',
            'ad_campaign_delete',
            'ad_campaign_access',
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
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->updateOrInsert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }

    public function down(): void
    {
        $permissionIds = DB::table('permissions')
            ->whereIn('title', [
                'ad_campaign_create',
                'ad_campaign_edit',
                'ad_campaign_show',
                'ad_campaign_delete',
                'ad_campaign_access',
            ])
            ->pluck('id');

        DB::table('permission_role')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();
    }
};
