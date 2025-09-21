<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->index();
            $table->text('title')->nullable();
            $table->longText('body')->nullable();
            $table->text('url')->nullable();
            $table->string('category')->nullable()->index();
            $table->timestamp('published_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('url_hash', 100)->unique();
            $table->timestamps();

            if (DB::getDriverName() == 'mysql') {
                $table->fullText(['title', 'body']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
