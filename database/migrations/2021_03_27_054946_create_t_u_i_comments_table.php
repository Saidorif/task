<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTUICommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_u_i_comments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('user_id');
            $table->integer('task_user_item_id');
            $table->string('type')->default('reject');
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
        Schema::dropIfExists('t_u_i_comments');
    }
}
