<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteResidentRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('complaints_resident_id_foreign');
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
        Schema::table('financials', function (Blueprint $table) {
            $table->dropForeign('financials_resident_id_foreign');
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('resident_id');
            $table->unsignedBigInteger('requested_by')->change();
            $table->foreign('requested_by')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
        Schema::table('resident_occupants', function (Blueprint $table) {
            $table->dropForeign('resident_occupants_resident_id_foreign');
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
        Schema::table('resident_pets', function (Blueprint $table) {
            $table->dropForeign('resident_pets_resident_id_foreign');
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
        Schema::table('resident_vehicles', function (Blueprint $table) {
            $table->dropForeign('resident_vehicles_resident_id_foreign');
            $table->unsignedBigInteger('resident_id')->change();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
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
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign("resident_id");
        });
        Schema::table('financials', function (Blueprint $table) {
            $table->dropForeign("resident_id");
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign("requested_by");
        });
        Schema::table('resident_occupants', function (Blueprint $table) {
            $table->dropForeign("resident_id");
        });
        Schema::table('resident_pets', function (Blueprint $table) {
            $table->dropForeign("resident_id");
        });
        Schema::table('resident_vehicles', function (Blueprint $table) {
            $table->dropForeign("resident_id");
        });
    }
}
