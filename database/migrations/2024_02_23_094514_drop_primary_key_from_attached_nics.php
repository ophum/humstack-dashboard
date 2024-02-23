<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPrimaryKeyFromAttachedNics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attached_nics', function (Blueprint $table) {
            $table->dropForeign('attached_nics_machine_id_foreign');
            $table->dropForeign('attached_nics_network_id_foreign');
            $table->dropPrimary(['machine_id', 'network_id']);
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('network_id')->references('id')->on('networks');
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
            $table->primary(['machine_id', 'network_id']);
        });
    }
}
