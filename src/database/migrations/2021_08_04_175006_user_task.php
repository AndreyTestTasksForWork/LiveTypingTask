<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_task', function (Blueprint $table) {
            $table->id('id');
            $table->integer('status');
            $table->foreignId('category_id')->unsigned();
            $table->foreignId('task_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')->on('category')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')->on('task')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_task');
    }
}
