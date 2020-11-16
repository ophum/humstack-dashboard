<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteToDeploySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deploy_settings', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['problem_id']);
            $table->dropForeign(['node_id']);
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deploy_settings', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['problem_id']);
            $table->dropForeign(['node_id']);
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('problem_id')->references('id')->on('problems');
            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }
}
