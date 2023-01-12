<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Inertia\Inertia;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Inertia
     */
    public function index()
    {
        return Inertia::render('Blog/Index', [
            'blogList' => Blog::where('user_id', Auth()->user()->id)->latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Inertia
     */
    public function create()
    {
        return Inertia::render('Blog/EditBlog');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Inertia
     */
    public function store(BlogRequest $blogRequest)
    {
        Blog::create(array_merge($blogRequest->validated(), [
            'user_id' => auth()->user()->id
        ]));

        return Redirect::route('blog.index')
            ->with('message', 'Data Added Sucessfully !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Inertia\Inertia
     */
    public function edit(Blog $blog)
    {
        if($blog->user_id != auth()->user()->id){
            return Redirect::route('blog.index')
                ->with('message', 'Unauthorized Access !');
        }

        return Inertia::render('Blog/EditBlog', ['data' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Inertia\Inertia
     */
    public function update(BlogRequest $blogRequest, Blog $blog)
    {

        $blog->update($blogRequest->validated());

        return Redirect::route('blog.index')
            ->with('message', 'Data Updated Sucessfully !');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Inertia\Inertia
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return Redirect::route('blog.index')->with('message', 'Data removed Sucessfully !');
    }

    /**
     * Update blog view count.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Inertia\Inertia
     */
    public function updateBlogView($blogId) {

        Blog::findOrFail($blogId)->increment('view');
    }
}
