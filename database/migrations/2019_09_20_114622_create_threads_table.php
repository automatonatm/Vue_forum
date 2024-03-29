<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('channel_id');
            $table->unsignedBigInteger('replies_count')->default(0);
            $table->unsignedBigInteger('best_reply_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->longText('body');
            $table->boolean('locked')->default(false);

/*
            $table->foreign('best_reply_id')
                ->references('id')
                ->on('replies')
                ->onDelete('set null');*/

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
        Schema::dropIfExists('threads');
    }
}
