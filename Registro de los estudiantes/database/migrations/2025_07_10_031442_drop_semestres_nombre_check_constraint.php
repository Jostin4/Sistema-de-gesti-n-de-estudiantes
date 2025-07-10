<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('ALTER TABLE semestres DROP CONSTRAINT IF EXISTS semestres_nombre_check;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No es necesario volver a agregar la restricción
    }
};
