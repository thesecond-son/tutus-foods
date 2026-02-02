<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\HomepageSetting;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->get();
        $blogs = Blog::with('categories')->latest()->paginate(12);
        $homepage = HomepageSetting::firstOrFail();

        return view('web.blog.index', compact('categories', 'blogs', 'homepage'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $blogs = $category->blogs()->with('categories')->latest()->paginate(12);
        $categories = BlogCategory::withCount('blogs')->get();

        return view('web.blog.index', compact('categories', 'blogs', 'category'));
    }

    public function show($slug)
    {
        $blog = Blog::with('categories', 'comments')->where('slug', $slug)->firstOrFail();
        return view('web.blog.show', compact('blog'));
    }

    public function storeComment(Request $request, Blog $blog)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'comment' => 'required|string',
        ]);

        $blog->comments()->create([
            'customer_name' => $request->customer_name,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Comment submitted!');
    }
}