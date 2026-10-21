<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_positions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('row');
            $table->unsignedTinyInteger('sort_order');
            $table->unsignedSmallInteger('desktop_width');
            $table->unsignedSmallInteger('desktop_height');
            $table->unsignedSmallInteger('mobile_width')->default(320);
            $table->unsignedSmallInteger('mobile_height')->default(100);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $positions = [
            ['home-01', 'Posição 1', 1, 1, 970, 250],
            ['home-02', 'Posição 2', 2, 2, 468, 60],
            ['home-03', 'Posição 3', 2, 3, 468, 60],
            ['home-04', 'Posição 4', 3, 4, 300, 250],
            ['home-05', 'Posição 5', 3, 5, 300, 250],
            ['home-06', 'Posição 6', 3, 6, 300, 250],
            ['home-07', 'Posição 7', 4, 7, 200, 200],
            ['home-08', 'Posição 8', 4, 8, 200, 200],
            ['home-09', 'Posição 9', 4, 9, 200, 200],
            ['home-10', 'Posição 10', 4, 10, 200, 200],
        ];

        foreach ($positions as [$code, $name, $row, $sortOrder, $width, $height]) {
            DB::table('ad_positions')->insert([
                'code' => $code,
                'name' => $name,
                'row' => $row,
                'sort_order' => $sortOrder,
                'desktop_width' => $width,
                'desktop_height' => $height,
                'mobile_width' => 320,
                'mobile_height' => 100,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_positions');
    }
};
