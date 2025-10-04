<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Events\PostCreate;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display all posts
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $posts = Post::get();

        return view('posts', compact('posts'));
    }

    /**
     * Store a new post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        // Create post
        $post = Post::create([
            'user_id' => auth()->id(),
            'title'   => $validatedData['title'],
            'body'    => $validatedData['body'],
        ]);

        // Fire event
        event(new PostCreate($post));

        // Redirect back with success message
        return back()->with('success', 'Post created successfully.');
    }
}
