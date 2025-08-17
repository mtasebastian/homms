<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditResidentSubtables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resident_occupants', function(Blueprint $table){
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');
        });

        Schema::table('resident_vehicles', function(Blueprint $table){
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');
        });

        Schema::table('resident_pets', function(Blueprint $table){
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                ->references('id')->on('residents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resident_occupants', function(Blueprint $table){
            $table->dropForeign(['resident_id']);
            $table->integer('resident_id')->change();
        });
        Schema::table('resident_vehicles', function(Blueprint $table){
            $table->dropForeign(['resident_id']);
            $table->integer('resident_id')->change();
        });
        Schema::table('resident_pets', function(Blueprint $table){
            $table->dropForeign(['resident_id']);
            $table->integer('resident_id')->change();
        });
    }
}
