<?php
// database/migrations/xxxx_xx_xx_create_chat_messages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // لتتبع جلسة المحادثة
            $table->text('user_message');
            $table->text('ai_response')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('conversation_context')->nullable(); // لحفظ سياق المحادثة
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
};