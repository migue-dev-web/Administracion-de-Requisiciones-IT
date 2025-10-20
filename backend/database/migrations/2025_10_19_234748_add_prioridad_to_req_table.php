<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('req_table', function (Blueprint $table) {
        $table->tinyInteger('prioridad')->default(3);
        $table->text('razon_prioridad')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('req', function (Blueprint $table) {
            //
        });
    }
};
