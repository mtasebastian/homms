<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string("house_number", 20);
            $table->string("block", 20);
            $table->string("lot", 20);
            $table->string("street", 200);
            $table->string("phase", 20);
            $table->integer("barangay_id");
            $table->integer("city_id");
            $table->integer("province_id");
            $table->string("unit_area", 20)->nullable();
            $table->date("move_in_date");
            $table->string("house_color", 50);
            $table->integer("renovated")->default(0);
            $table->string("last_name", 100);
            $table->string("first_name", 100);
            $table->string("middle_name", 100)->nullable();
            $table->string("home_address", 200);
            $table->string("other_address", 200)->nullable();
            $table->string("mobile_number", 50);
            $table->string("email_address", 100);
            $table->string("citizenship", 50);
            $table->string("date_of_birth", 50);
            $table->integer("age");
            $table->string("place_of_birth", 200);
            $table->string("civil_status", 20)->default("Single");
            $table->string("gender", 10);
            $table->string("occupation", 100)->nullable();
            $table->string("company_name", 150)->nullable();
            $table->string("company_address", 200)->nullable();
            $table->string("contact_person", 150)->nullable();
            $table->string("contact_person_number", 50)->nullable();
            $table->integer("home_status")->default(0);
            $table->integer("homeowner_status")->default(0);
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
        Schema::dropIfExists('residents');
    }
}
