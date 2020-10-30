<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachedStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attached_storages', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('storage_id');
            $table->integer('order')->default(1);

            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('storage_id')->references('id')->on('storages');

            $table->primary(['machine_id', 'storage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attached_storages');
    }
}
