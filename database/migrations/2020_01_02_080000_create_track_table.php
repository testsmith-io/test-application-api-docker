<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->unsignedBigInteger('album_id');
            $table->unsignedBigInteger('mediatype_id');
            $table->unsignedBigInteger('genre_id');
            $table->string('composer', 220)->nullable();
            $table->integer('milliseconds');
            $table->integer('bytes');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->foreign('album_id')->references('id')->on('album');
            $table->foreign('mediatype_id')->references('id')->on('mediatype');
            $table->foreign('genre_id')->references('id')->on('genre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track');
    }
}
