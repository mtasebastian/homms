<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentOccupantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_occupants', function (Blueprint $table) {
            $table->id();
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
            $table->string("last_name", 100);
            $table->string("first_name", 100);
            $table->string("middle_name", 100)->nullable();
            $table->integer("age");
            $table->string("gender", 50);
            $table->string("relationship_to_homeowner", 100);
            $table->string("email_address", 150)->nullable();
            $table->string("mobile_number", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_occupants');
    }
}
