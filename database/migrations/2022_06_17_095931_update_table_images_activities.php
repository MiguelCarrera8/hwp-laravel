<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableImagesActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images_activities', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropColumn('activity_id');
            $table->unsignedBigInteger('user_id')->after('id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('like_count')->default(0)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('like_count');
        });
    }
}
