<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::with('user')
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('stories.index', compact('stories'));
    }

    public function show($id)
    {
        $story = Story::with('user')->published()->findOrFail($id);
        $story->incrementViews();

        $relatedStories = Story::with('user')
            ->where('id', '!=', $story->id)
            ->published()
            ->limit(3)
            ->get();

        return view('stories.show', compact('story', 'relatedStories'));
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('stories', 'public');
                $images[] = $path;
            }
        }

        $story = Auth::user()->stories()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'images' => $images,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        return redirect()->route('stories.show', $story->id)
            ->with('success', 'Story created successfully!');
    }

    public function edit($id)
    {
        $story = Story::where('user_id', Auth::id())->findOrFail($id);
        return view('stories.edit', compact('story'));
    }

    public function update(Request $request, $id)
    {
        $story = Story::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $images = $story->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('stories', 'public');
                $images[] = $path;
            }
        }

        $story->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'images' => $images,
            'is_published' => $request->has('is_published') ? true : false,
        ]);

        return redirect()->route('stories.show', $story->id)
            ->with('success', 'Story updated successfully!');
    }

    public function destroy($id)
    {
        $story = Story::where('user_id', Auth::id())->findOrFail($id);

        // Delete images
        if ($story->images) {
            foreach ($story->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $story->delete();

        return redirect()->route('profile.stories')
            ->with('success', 'Story deleted successfully!');
    }

    public function like($id)
    {
        $story = Story::findOrFail($id);
        $story->incrementLikes();

        return response()->json([
            'success' => true,
            'likes_count' => $story->likes_count,
        ]);
    }
}
