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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('primary_image');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->text('primary_image')->nullable();
        });

        Schema::table('event_images', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('event_images', function (Blueprint $table) {
            $table->text('image_data')->nullable();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('primary_image');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('primary_image')->nullable();
        });

        Schema::table('event_images', function (Blueprint $table) {
            $table->dropColumn('image_data');
        });

        Schema::table('event_images', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });
    }

};
