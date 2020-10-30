<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachedNicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attached_nics', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('network_id');
            $table->string('ipv4_address');
            $table->string('default_gateway');
            $table->string('nameserver')->default('8.8.8.8');
            $table->integer('order')->default(1);

            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('network_id')->references('id')->on('networks');

            $table->primary(['machine_id', 'network_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attached_nics');
    }
}
