<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::whereNotNull('published_at')
                     ->where('published_at', '<=', now())
                     ->latest()
                     ->paginate(9);
                     
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->firstOrFail();

        $relatedPosts = Post::where('id', '!=', $post->id)
                            ->whereNotNull('published_at')
                            ->where('published_at', '<=', now())
                            ->latest()
                            ->take(3)
                            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
