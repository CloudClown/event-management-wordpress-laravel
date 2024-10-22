<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   class UpdateImageColumnsToLongtext extends Migration
   {
       public function up()
       {
           Schema::table('events', function (Blueprint $table) {
               $table->longText('primary_image')->change();
           });

           Schema::table('event_images', function (Blueprint $table) {
               $table->longText('image_data')->change();
           });
       }

       public function down()
       {
           Schema::table('events', function (Blueprint $table) {
               $table->text('primary_image')->change();
           });

           Schema::table('event_images', function (Blueprint $table) {
               $table->text('image_data')->change();
           });
       }
   }