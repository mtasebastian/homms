<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
            $table->string("complaint_type", 100);
            $table->integer("report_to");
            $table->integer("complaint_to")->nullable();
            $table->string("purpose");
            $table->string("details");
            $table->string("status", 50);
            $table->softDeletes();
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
        Schema::dropIfExists('complaints');
    }
}
