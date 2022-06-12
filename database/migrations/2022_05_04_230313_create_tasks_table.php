<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained();
            $table->unsignedBigInteger('perpetrator_id');
            $table->foreign('perpetrator_id')->references('id')->on('users');
            $table->unsignedBigInteger('description_task_id');
            $table->foreign('description_task_id')->references('id')->on('description_tasks');
            $table->string('estimate', 15)->default('Не оценено');
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('tasks');
    }
};
