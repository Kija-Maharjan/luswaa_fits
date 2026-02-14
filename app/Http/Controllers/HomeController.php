<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('user')
            ->available()
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $recentStories = Story::with('user')
            ->published()
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact('featuredProducts', 'recentStories'));
    }
}
