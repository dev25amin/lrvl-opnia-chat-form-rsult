<?php
// database/migrations/xxxx_xx_xx_create_text_requests_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('text_requests', function (Blueprint $table) {
            $table->id();
            $table->text('original_text');
            $table->text('translated_text')->nullable();
            $table->text('rewritten_text')->nullable(); // تطابق مع الـ Job
            $table->json('verbs')->nullable();
            $table->json('nouns')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('text_requests');
    }
};