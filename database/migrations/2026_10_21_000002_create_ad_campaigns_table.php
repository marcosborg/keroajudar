<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_position_id')->constrained('ad_positions')->restrictOnDelete();
            $table->string('advertiser_name');
            $table->string('title');
            $table->string('destination_url', 2048);
            $table->string('status')->default('draft');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('targets_all_categories')->default(true);
            $table->json('target_categories')->nullable();
            $table->boolean('targets_all_regions')->default(true);
            $table->json('target_regions')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['ad_position_id', 'status', 'starts_at', 'ends_at'], 'ad_campaign_eligibility_idx');
        });

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
            $permissionIds = DB::table('permissions')->whereIn('title', $permissions)->pluck('id');
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
        Schema::dropIfExists('ad_campaigns');
        DB::table('permissions')->whereIn('title', [
            'ad_campaign_create', 'ad_campaign_edit', 'ad_campaign_show',
            'ad_campaign_delete', 'ad_campaign_access',
        ])->delete();
    }
};
