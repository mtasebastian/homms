<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_setup', function (Blueprint $table) {
            $table->id();
            $table->string("setting_name", 200);
            $table->string("setting_type", 20)->nullable();
            $table->string("text", 200)->nullable();
            $table->string('mime')->nullable();
            $table->binary('content')->nullable();
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
        Schema::dropIfExists('sys_setup');
    }
}
