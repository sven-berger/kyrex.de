<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('wissensportal_pages') || !Schema::hasColumn('wissensportal_pages', 'title')) {
            return;
        }

        Schema::table('wissensportal_pages', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('wissensportal_pages') || Schema::hasColumn('wissensportal_pages', 'title')) {
            return;
        }

        Schema::table('wissensportal_pages', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
        });
    }
};
