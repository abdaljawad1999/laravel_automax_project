<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->with(['user'])->withCount('likes')->get();
       
       
        return response($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:5048',
            'pdf' => 'required|mimes:pdf|max:5048',
        ]);
         // Handle image upload
         if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('images', 'public');
        }
    
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $pdfPath = $pdf->store('pdfs', 'public');
        }
         // Create a new post in the database
        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image_path' => isset($imagePath) ? $imagePath : null,
            'pdf_path' => isset($pdfPath) ? $pdfPath : null,
        ]);
    
        // Return the created post as a JSON response
        return response()->json($post, 201);}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
        // Check if the request contains any data in 'form-data' format
        if ($request->hasAny(['title', 'body', 'image', 'pdf'])) {
            // Find the post by ID
            $post = Post::find($id);
    
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
    
            // Update the fields if they exist in the request
            if ($request->has('title')) {
                $post->title = $request->input('title');
            }
            if ($request->has('body')) {
                $post->body = $request->input('body');
            }
    
            // Handle image update if a new image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');
                $post->image_path = $imagePath;
            }
    
            // Handle PDF update if a new PDF is provided
            if ($request->hasFile('pdf')) {
                $pdf = $request->file('pdf');
                $pdfPath = $pdf->store('pdfs', 'public');
                $post->pdf_path = $pdfPath;
            }
    
            // Save the updated post to the database
            $post->save();
    
            return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
        } else {
            // If no fields are provided in the request, return an error response
            return response()->json(['message' => 'No fields provided in the request'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return response(['message' => 'Deleted successfully!']);

    }
    
    public function search($name)
    {
       return Post::where('title','like','%'.$name.'%')->get();

    }
}