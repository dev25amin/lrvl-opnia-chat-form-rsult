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
    Schema::create('text_analyses', function (Blueprint $table) {
        $table->id();
$table->text('input_text');
$table->text('translated_to_en')->nullable();
$table->text('translated_to_ar')->nullable();


        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_analyses');
    }
};
