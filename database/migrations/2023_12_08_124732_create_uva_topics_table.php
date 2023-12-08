<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('uva_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('serial');
            $table->text('title');
            $table->text('topic_url');
            $table->integer('star');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('uva_topics');
    }
};
