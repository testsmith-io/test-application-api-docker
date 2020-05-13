<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname', 40);
            $table->string('lastname', 20);
            $table->string('company', 80)->nullable();
            $table->string('address', 70);
            $table->string('city', 40);
            $table->string('state', 40)->nullable();
            $table->string('country', 40);
            $table->string('postalcode', 10)->nullable();
            $table->string('phone', 24)->nullable();
            $table->string('fax', 24)->nullable();
            $table->string('email', 60)->unique();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('support_rep_id');
            $table->timestamps();
            $table->foreign('support_rep_id')->references('id')->on('employee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
