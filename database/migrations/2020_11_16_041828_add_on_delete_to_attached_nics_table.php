<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteToAttachedNicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attached_nics', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
            $table->dropForeign(['network_id']);
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attached_nics', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
            $table->dropForeign(['network_id']);
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('network_id')->references('id')->on('networks');
        });
    }
}
