<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul', 255);
            $table->string('kategori', 50);
            $table->text('isi');
            $table->string('gambar')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('lampiran_nama')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasis');
    }
};
