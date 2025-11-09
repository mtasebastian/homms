<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string("request_type", 150);
            $table->string("address");
            $table->string("type", 150);
            $table->string("details", 150);
            $table->date("pullout_delivery_date");
            $table->date("valid_from");
            $table->date("valid_to");
            $table->foreign('requested_by')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
            $table->integer("approved_by")->nullable();
            $table->integer("checked_by")->nullable();
            $table->string("request_status")->default("Pending");
            $table->string("qr_code", 20);
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
        Schema::dropIfExists('requests');
    }
}
