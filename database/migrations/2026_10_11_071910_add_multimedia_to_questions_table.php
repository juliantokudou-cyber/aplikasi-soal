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
        Schema::table('questions', function (Blueprint $table) {
            // Tipe media: 'image', 'video', 'audio', atau null jika teks saja
        $table->string('media_type')->nullable()->after('question_text');
        $table->string('media_file')->nullable()->after('media_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'media_file']);
        });
    }
};
