<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->nullable()->index();
            $table->string('title');
            $table->string('author');
            $table->text('publisher')->nullable();          // Alterado para text/string ampla
            $table->text('publication_year')->nullable();   // Aceita observações de ano longas
            $table->string('publication_city')->nullable();
            $table->text('first_edition_year')->nullable(); // Aceita observações de ano longas
            $table->string('type')->default('Literatura')->index();
            $table->string('discipline')->nullable()->index();
            $table->text('location_shelf')->nullable();
            $table->enum('status', ['available', 'borrowed', 'reserved', 'maintenance'])->default('available');
            $table->boolean('is_printed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
