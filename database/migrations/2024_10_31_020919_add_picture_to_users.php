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
        Schema::table('users', function (Blueprint $table) {
            // menambahkan kolom photo
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // menambahkan drop untuk menghapus kolom
            $table->dropColumn('photo');
        });
    }
};

// jika sudah menambhakna function up dan down lalu lakukan migration untuk menambahkan kolom baru pada database.
