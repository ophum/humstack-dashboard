<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteToAttachedStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attached_storages', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
            $table->dropForeign(['storage_id']);
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attached_storages', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
            $table->dropForeign(['storage_id']);
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('storage_id')->references('id')->on('storages');
        });
    }
}
