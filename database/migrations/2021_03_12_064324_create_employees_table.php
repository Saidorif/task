<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surename');
            $table->string('lastname');
            $table->date('birthday');
            $table->string('birthplace')->nullable();
            $table->string('gender');
            $table->foreignId('p_id');
            $table->foreignId('e_id');
            $table->string('status');
            $table->integer('region_id')->nullable();
            $table->integer('area_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
