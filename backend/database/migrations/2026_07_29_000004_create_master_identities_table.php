<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_identities', function (Blueprint $table) {
            $table->id();
            $table->string('identity_number')->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('role_type', ['mahasiswa', 'dosen'])->default('mahasiswa');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['identity_number', 'role_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_identities');
    }
};
