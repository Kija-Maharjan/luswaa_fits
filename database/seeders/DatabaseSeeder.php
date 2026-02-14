<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample users
        $users = [];
        
        $users[] = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'both',
            'bio' => 'Fashion enthusiast and streetwear collector. Love vintage finds!',
            'phone' => '555-0101',
            'address' => '123 Fashion St, Style City',
        ]);

        $users[] = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'bio' => 'Curating the best vintage pieces from the 90s and 2000s.',
            'phone' => '555-0102',
            'address' => '456 Trend Ave, Fashion Town',
        ]);

        $users[] = User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'bio' => 'Always looking for unique streetwear pieces.',
            'phone' => '555-0103',
        ]);

        // Sample products
        $categories = ['Tops', 'Bottoms', 'Outerwear', 'Shoes', 'Accessories'];
        $conditions = ['new', 'like_new', 'good', 'fair'];
        $brands = ['Nike', 'Adidas', 'Supreme', 'Carhartt', 'Levi\'s', 'Vintage', 'Thrifted'];

        $productData = [
            [
                'title' => 'Vintage Nike Windbreaker',
                'description' => 'Classic 90s Nike windbreaker in excellent condition. Features the iconic swoosh logo and vibrant colors.',
                'price' => 45.00,
                'size' => 'L',
                'brand' => 'Nike',
                'condition' => 'like_new',
                'category' => 'Outerwear',
            ],
            [
                'title' => 'Supreme Box Logo Hoodie',
                'description' => 'Authentic Supreme box logo hoodie from Fall/Winter 2020. Barely worn, no flaws.',
                'price' => 650.00,
                'size' => 'M',
                'brand' => 'Supreme',
                'condition' => 'new',
                'category' => 'Tops',
            ],
            [
                'title' => 'Vintage Levi\'s 501 Jeans',
                'description' => 'Classic Levi\'s 501 jeans with perfect fading. True vintage piece from the 80s.',
                'price' => 65.00,
                'size' => '32x34',
                'brand' => 'Levi\'s',
                'condition' => 'good',
                'category' => 'Bottoms',
            ],
            [
                'title' => 'Carhartt Work Jacket',
                'description' => 'Durable Carhartt work jacket. Well-worn but still lots of life left. Perfect workwear aesthetic.',
                'price' => 85.00,
                'size' => 'XL',
                'brand' => 'Carhartt',
                'condition' => 'fair',
                'category' => 'Outerwear',
            ],
            [
                'title' => 'Adidas Superstar Sneakers',
                'description' => 'Classic Adidas Superstars in white with black stripes. Size 10, lightly used.',
                'price' => 55.00,
                'size' => '10',
                'brand' => 'Adidas',
                'condition' => 'good',
                'category' => 'Shoes',
            ],
            [
                'title' => 'Vintage Band T-Shirt',
                'description' => 'Authentic vintage band tee from the 90s. Single stitch construction, soft and faded.',
                'price' => 35.00,
                'size' => 'M',
                'brand' => 'Vintage',
                'condition' => 'good',
                'category' => 'Tops',
            ],
            [
                'title' => 'Nike Air Max 97',
                'description' => 'Nike Air Max 97 in silver bullet colorway. Great condition, minimal wear.',
                'price' => 120.00,
                'size' => '9',
                'brand' => 'Nike',
                'condition' => 'like_new',
                'category' => 'Shoes',
            ],
            [
                'title' => 'Thrifted Flannel Shirt',
                'description' => 'Cozy flannel shirt in red and black plaid. Perfect for layering.',
                'price' => 20.00,
                'size' => 'L',
                'brand' => 'Thrifted',
                'condition' => 'good',
                'category' => 'Tops',
            ],
        ];

        foreach ($productData as $data) {
            Product::create([
                'user_id' => $users[rand(0, 1)]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'price' => $data['price'],
                'size' => $data['size'],
                'brand' => $data['brand'],
                'condition' => $data['condition'],
                'category' => $data['category'],
                'status' => 'available',
            ]);
        }

        // Sample stories
        $storyData = [
            [
                'title' => 'My Journey Into Vintage Fashion',
                'content' => 'It all started when I found a vintage Levi\'s jacket at a thrift store. The quality, the craftsmanship, the story behind it - I was hooked. Since then, I\'ve been collecting and curating vintage pieces, each with its own unique history. There\'s something special about wearing clothes that have lived a life before you.',
            ],
            [
                'title' => 'Building a Sustainable Wardrobe',
                'content' => 'In a world of fast fashion, I decided to take a different approach. Instead of buying new every season, I started thrifting and buying second-hand. Not only is it better for the environment, but you find such unique pieces that no one else has. My wardrobe has become a collection of stories, not just clothes.',
            ],
            [
                'title' => 'The Art of Styling Vintage',
                'content' => 'Styling vintage pieces with modern fashion is an art form. The key is balance - pair a vintage band tee with contemporary jeans, or throw a vintage jacket over a modern outfit. It\'s about creating a look that\'s uniquely yours while honoring the history of each piece.',
            ],
        ];

        foreach ($storyData as $data) {
            Story::create([
                'user_id' => $users[rand(0, 2)]->id,
                'title' => $data['title'],
                'content' => $data['content'],
                'is_published' => true,
                'likes_count' => rand(5, 50),
                'views' => rand(20, 200),
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Sample user credentials:');
        $this->command->info('Email: john@example.com | Password: password123');
        $this->command->info('Email: jane@example.com | Password: password123');
        $this->command->info('Email: mike@example.com | Password: password123');
    }
}
