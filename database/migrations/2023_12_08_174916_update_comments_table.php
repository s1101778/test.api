<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            //$table->foreignId('parent_comment_id')->after('content')->nullable()->constrained()->onDelete('cascade')->comment('外鍵_父comment_id');

            $table->unsingedBigInteger('parent_comment_id')->after('id')->nullable();
            $table->foregin('parent_comment_id')->references('id')->on('comments')->onDelete('cascade')->comment('外鍵_父comment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_comment_id');
        });
    }
};
