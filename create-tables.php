<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$app = require 'bootstrap/app.php';
$db = $app->make('db');

try {
    echo "Creating tables...\n";
    
    // Users table
    if (!Schema::connection('sqlite')->hasTable('users')) {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('role', ['buyer', 'seller', 'both'])->default('both');
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
        echo "✓ Users table created\n";
    }
    
    // Products table
    if (!Schema::connection('sqlite')->hasTable('products')) {
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
        echo "✓ Products table created\n";
    }
    
    // Carts table
    if (!Schema::connection('sqlite')->hasTable('carts')) {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->unique(['user_id', 'product_id']);
        });
        echo "✓ Carts table created\n";
    }
    
    // Orders table
    if (!Schema::connection('sqlite')->hasTable('orders')) {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('shipping_address');
            $table->timestamps();
        });
        echo "✓ Orders table created\n";
    }
    
    // Order items table
    if (!Schema::connection('sqlite')->hasTable('order_items')) {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('set null')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
        echo "✓ Order items table created\n";
    }
    
    // Stories table
    if (!Schema::connection('sqlite')->hasTable('stories')) {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->json('images')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();
            
            $table->index('published');
            $table->index('user_id');
        });
        echo "✓ Stories table created\n";
    }
    
    echo "\n✅ All tables created successfully!\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
