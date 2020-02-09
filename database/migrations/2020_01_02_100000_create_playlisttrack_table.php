<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylisttrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlisttrack', function (Blueprint $table) {
            $table->unsignedBigInteger('playlist_id');
            $table->unsignedBigInteger('track_id');
            $table->foreign('playlist_id')->references('id')->on('playlist');
            $table->foreign('track_id')->references('id')->on('track');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlisttrack');
    }
}
