<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->date('draw_date')->nullable();
            $table->string('link_url', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        $permissions = [
            'advertisement_create',
            'advertisement_edit',
            'advertisement_show',
            'advertisement_delete',
            'advertisement_access',
        ];

        foreach ($permissions as $title) {
            DB::table('permissions')->updateOrInsert(
                ['title' => $title],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $adminRoleId = DB::table('roles')->where('title', 'Admin')->value('id');
        if ($adminRoleId) {
            $permissionIds = DB::table('permissions')->whereIn('title', $permissions)->pluck('id')->all();

            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->updateOrInsert(
                    ['role_id' => $adminRoleId, 'permission_id' => $permissionId],
                    ['role_id' => $adminRoleId, 'permission_id' => $permissionId]
                );
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('advertisements');

        DB::table('permissions')
            ->whereIn('title', [
                'advertisement_create',
                'advertisement_edit',
                'advertisement_show',
                'advertisement_delete',
                'advertisement_access',
            ])
            ->delete();
    }
}
