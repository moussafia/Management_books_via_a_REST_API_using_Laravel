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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('autor');
            $table->string('isbn',13);
            $table->date('date_pub');
            $table->integer('nmb_page');
            $table->text('contenu');
            $table->string('emplacement');
            $table->unsignedBigInteger('collection_id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->string('statut');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
