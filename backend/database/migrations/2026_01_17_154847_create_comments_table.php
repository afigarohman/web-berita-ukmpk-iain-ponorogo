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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users (Siapa yang komen?)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke tabel posts (Komen di berita mana?)
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            
            // Isi komentarnya
            $table->text('body');
            
            // Status persetujuan (Default: false / Belum disetujui)
            $table->boolean('is_approved')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
