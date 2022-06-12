<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTableActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('capacity');
            $table->string('description')->nullable()->after('name');
            $table->string('city')->nullable()->after('location');
            $table->string('hour')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('capacity')->nullable();
            $table->dropColumn('description');
            $table->dropColumn('city');
            $table->dropColumn('hour');
        });
    }
}
