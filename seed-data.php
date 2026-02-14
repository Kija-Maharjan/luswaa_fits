<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

try {
    echo "Creating sample users...\n";
    
    // Create users if they don't exist
    $users = [];
    
    $user1 = User::firstOrCreate(
        ['email' => 'john@example.com'],
        [
            'name' => 'John Doe',
            'password' => Hash::make('password123'),
            'role' => 'both',
            'bio' => 'Fashion enthusiast',
            'phone' => '555-0101',
        ]
    );
    $users[] = $user1;
    echo "✓ User 1 created\n";
    
    $user2 = User::firstOrCreate(
        ['email' => 'jane@example.com'],
        [
            'name' => 'Jane Smith',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'bio' => 'Vintage collector',
            'phone' => '555-0102',
        ]
    );
    $users[] = $user2;
    echo "✓ User 2 created\n";
    
    echo "\nCreating sample products...\n";
    
    // Sample products data
    $productsData = [
        [
            'user_id' => $users[0]->id,
            'title' => 'Supreme Box Logo Hoodie',
            'description' => 'Authentic Supreme box logo hoodie in red. Gently worn, excellent condition.',
            'price' => 450.00,
            'brand' => 'Supreme',
            'size' => 'M',
            'condition' => 'good',
            'category' => 'Outerwear',
            'status' => 'available',
        ],
        [
            'user_id' => $users[1]->id,
            'title' => 'Vintage Nike Air Max 90',
            'description' => '1990s Nike Air Max 90 in original colorway. Collectible sneaker.',
            'price' => 320.00,
            'brand' => 'Nike',
            'size' => '10',
            'condition' => 'like_new',
            'category' => 'Shoes',
            'status' => 'available',
        ],
        [
            'user_id' => $users[0]->id,
            'title' => 'Carhartt WIP Work Jacket',
            'description' => 'Brown Carhartt work jacket, practically never worn.',
            'price' => 180.00,
            'brand' => 'Carhartt',
            'size' => 'L',
            'condition' => 'new',
            'category' => 'Outerwear',
            'status' => 'available',
        ],
        [
            'user_id' => $users[1]->id,
            'title' => 'Levi\'s 501 Vintage Jeans',
            'description' => 'Classic Levi\'s 501 from the 80s. Great fit and patina.',
            'price' => 95.00,
            'brand' => 'Levi\'s',
            'size' => '32',
            'condition' => 'good',
            'category' => 'Bottoms',
            'status' => 'available',
        ],
        [
            'user_id' => $users[0]->id,
            'title' => 'Adidas Superstar Sneakers',
            'description' => 'White Adidas Superstars, clean and ready to wear.',
            'price' => 75.00,
            'brand' => 'Adidas',
            'size' => '9',
            'condition' => 'good',
            'category' => 'Shoes',
            'status' => 'available',
        ],
        [
            'user_id' => $users[1]->id,
            'title' => 'Thrifted Band T-Shirt',
            'description' => 'Vintage rare band tee. Perfect for collectors.',
            'price' => 55.00,
            'brand' => 'Vintage',
            'size' => 'M',
            'condition' => 'fair',
            'category' => 'Tops',
            'status' => 'available',
        ],
        [
            'user_id' => $users[0]->id,
            'title' => 'Designer Crossbody Bag',
            'description' => 'Authentic designer crossbody bag. Minimal wear.',
            'price' => 280.00,
            'brand' => 'Designer',
            'size' => 'One Size',
            'condition' => 'like_new',
            'category' => 'Accessories',
            'status' => 'available',
        ],
        [
            'user_id' => $users[1]->id,
            'title' => 'Wool Peacoat Jacket',
            'description' => 'Classic wool peacoat, navy blue. Perfect for winter.',
            'price' => 150.00,
            'brand' => 'Vintage',
            'size' => 'S',
            'condition' => 'good',
            'category' => 'Outerwear',
            'status' => 'available',
        ],
    ];
    
    foreach ($productsData as $data) {
        $product = Product::firstOrCreate(
            ['title' => $data['title']],
            $data
        );
        echo "✓ Created: {$product->title}\n";
    }
    
    echo "\n✅ Sample data seeded successfully!\n";
    echo "Total products: " . Product::count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
