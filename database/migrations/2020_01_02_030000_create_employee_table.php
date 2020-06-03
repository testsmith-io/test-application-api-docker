<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lastname', 20);
            $table->string('firstname', 20);
            $table->string('title', 30);
            $table->unsignedBigInteger('reports_to')->nullable();
            $table->date('birthdate');
            $table->date('hiredate');
            $table->string('address', 70);
            $table->string('city', 40);
            $table->string('state', 40);
            $table->string('country', 40);
            $table->string('postalcode', 10);
            $table->string('phone', 24);
            $table->string('fax', 24);
            $table->string('email', 60);
            $table->timestamps();
            $table->foreign('reports_to')->references('id')->on('employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
