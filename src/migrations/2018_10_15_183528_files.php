<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    public function up()
    {
        Schema::create('_files', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('url');
            $table->string('extension');
            $table->string('type');
            $table->string('size');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('_files_relation', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('file_model');
            $table->integer('ref_id');
            $table->unsignedInteger('file_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('_files')
                ->onDelete('cascade'); 

            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::drop('_files');
        Schema::drop('_files_relation');
    }
}
