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
        Schema::create('req_table', function (Blueprint $table) {
        $table->id();
        $table->string('problema');
        $table->string('requisitor');
        $table->string('tecnico')->nullable();
        $table->string('media');
        $table->text('descripcion');
        $table->string('ubicacion');
        $table->boolean('status');
        $table->dateTime('fecha_creacion')->nullable(); 
        $table->dateTime('fecha_finalizacion')->nullable(); 
        $table->timestamps(); // created_at y updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
