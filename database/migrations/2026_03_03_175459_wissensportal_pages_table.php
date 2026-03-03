<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wissensportal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->text('content')->default('');
            $table->string('snippet_1_title');
            $table->text('snippet_1');
            $table->string('snippet_2_title')->default('');
            $table->text('snippet_2')->default('');
            $table->string('snippet_3_title')->default('');
            $table->text('snippet_3')->default('');
            $table->string('snippet_4_title')->default('');
            $table->text('snippet_4')->default('');
            $table->string('snippet_5_title')->default('');
            $table->text('snippet_5')->default('');
            $table->foreignId('category_id')->constrained('wissensportal_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wissensportal_pages');
    }
};
