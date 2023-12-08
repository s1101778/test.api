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
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('likes')->default(0)->after('content');
            $table->json('mention')->nullable()->after('content')->comment('提及');
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade')->comment('外鍵_使用者ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('mention');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
