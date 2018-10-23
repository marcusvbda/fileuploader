<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    public function up()
    {
        Schema::create('_files_categories', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('name');
            $table->string('user_id')->nullable()->default(null);
            $table->string('user_type')->nullable()->default(null);
            $table->boolean('private')->default(0);
            $table->timestamps();
        });

        Schema::create('_files', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('user_id')->nullable()->default(null);
            $table->string('user_type')->nullable()->default(null);
            $table->boolean('private')->default(0);
            $table->string('description')->nullable();
            $table->string('dir');
            $table->string('extension');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('_files_relation', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->morphs("model");
            $table->integer('ordination')->default(0);
            $table->unsignedInteger('file_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('_files')
                ->onDelete('cascade'); 
            $table->timestamps();
        });


        Schema::create('_files_categories_relation', function (Blueprint $table) 
        {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->unsignedInteger('file_category_id');
            $table->foreign('file_category_id')
                ->references('id')
                ->on('_files_categories')
                ->onDelete('cascade'); 
            $table->unsignedInteger('file_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('_files')
                ->onDelete('cascade'); 
            
            $table->primary(['file_category_id','file_id']);    
            $table->timestamps();
        });
        
    }
    public function down()
    {
        Schema::drop('_files');
        Schema::drop('_files_categories');
        Schema::drop('_files_categories_relation');
        Schema::drop('_files_relation');
    }
}
