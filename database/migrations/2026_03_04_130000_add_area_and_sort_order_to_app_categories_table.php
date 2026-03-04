<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('app_categories', function (Blueprint $table) {
            $table->string('area')->default('index')->after('value');
            $table->unsignedInteger('sort_order')->default(0)->after('area');
        });

        DB::table('app_categories')
            ->whereRaw('LOWER(`value`) = ?', ['acp'])
            ->update(['area' => 'acp']);

        DB::table('app_categories')
            ->whereRaw('LOWER(`value`) = ?', ['index'])
            ->update(['area' => 'index']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_categories', function (Blueprint $table) {
            $table->dropColumn(['area', 'sort_order']);
        });
    }
};
