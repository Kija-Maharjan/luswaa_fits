<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('size')->nullable();
            $table->string('brand')->nullable();
            $table->enum('condition', ['new', 'like_new', 'good', 'fair'])->default('good');
            $table->json('images')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['available', 'sold', 'pending'])->default('available');
            $table->integer('views')->default(0);
            $table->timestamps();
            
            $table->index('status');
            $table->index('category');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
