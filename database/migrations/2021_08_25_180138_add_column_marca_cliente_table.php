<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMarcaClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente', function (Blueprint $table) {
              $table->string('gnpMarca')->nullable();
              $table->string('gnpsubMarca')->nullable();
              $table->string('anaMarca')->nullable();
              $table->string('anasubMarca')->nullable();
              $table->string('gsMarca')->nullable();
              $table->string('gssubMarca')->nullable();
              $table->string('qaMarca')->nullable();
              $table->string('qasubMarca')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cliente', function (Blueprint $table) {
            //
             $table->dropColumn('gnpMarca');
             $table->dropColumn('gnpsubMarca');
             $table->dropColumn('anaMarca');
             $table->dropColumn('anasubMarca');
             $table->dropColumn('gsMarca');
             $table->dropColumn('gssubMarca');
             $table->dropColumn('qaMarca');
             $table->dropColumn('qasubMarca');
        });
    }
}
