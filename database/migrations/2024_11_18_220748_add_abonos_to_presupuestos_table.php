<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('presupuestos', function (Blueprint $table) {
        $table->decimal('abonos', 10, 2)->default(0)->after('monto');
    });
}

public function down()
{
    Schema::table('presupuestos', function (Blueprint $table) {
        $table->dropColumn('abonos');
    });
}

};
