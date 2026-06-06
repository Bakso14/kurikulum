<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->integer('sks');
            $table->string('category'); // General Education, Basic Science & Math, Engineering Topics
            $table->integer('semester')->default(1); // Semester 1-8, atau 0 untuk "Peminatan/Belum Diplot"
            $table->integer('sort_order')->default(0); // Untuk urutan drag-and-drop
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};