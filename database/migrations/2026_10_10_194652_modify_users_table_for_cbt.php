<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengubah email agar boleh login tanpa username dulu saat awal regis
            $table->string('username')->nullable()->unique()->after('id');
            $table->date('birth_date')->after('email');
            $table->string('whatsapp')->after('birth_date');
            $table->boolean('is_approved')->default(false)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'birth_date', 'whatsapp', 'is_approved']);
        });
    }
};

