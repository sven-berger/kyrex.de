<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dummy_page_entries', function (Blueprint $table) {
            $table->string('title')->nullable()->unique()->after('id');
        });
    }
};
