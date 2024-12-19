<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    
    public function index()
    {
        $blogs = Blog::with('user')->where('status', 'published')->get();
        $user = Auth::user();
        return view('blogs.index', compact('blogs', 'user'));
    }

    public function show($id)
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('blogs.show', compact('blog'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ]);

        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'image_url' => $request->image_url,
            'status' => $request->status,
        ]);

        return redirect()->route('blogs.index');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        if (!Auth::user()->hasRole('Admin') && $blog->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to edit this blog.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ]);

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $request->image_url,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Blog updated successfully.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
    
        if (Auth::user()->hasRole('Admin')) {

            $blog->delete();
            return redirect()->back()->with('success', 'Blog deleted successfully.');
        }
    
        if (Auth::user()->hasRole('Blogger') && $blog->user_id == Auth::id()) {

            $blog->delete();
            return redirect()->back()->with('success', 'Your blog has been deleted successfully.');
        }

        return redirect()->back()->with('error', 'You are not authorized to delete this blog.');
    }
    }
