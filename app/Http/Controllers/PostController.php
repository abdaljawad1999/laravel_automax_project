<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth'])->only('store','destroy');
    // }

    public function index(Request $request)
    {
        $posts = Post::latest()->with(['user', 'likes'])->paginate(4); // Paginate the results
     
        if ($request->expectsJson()) {
            return PostResource::collection($posts); // Return JSON data if it's an AJAX request
        }
    
        return view('posts.index', ['posts' => $posts]); 
    }

    public function store(Request $request)
    {
        
        // Validate the request
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:5048', // Accept image files
            'pdf' => 'required|mimes:pdf|max:5048', // Accept PDF files
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Get the file extension
            $imageExtension = $request->file('image')->extension();
    
            // Generate a unique slug for the image
            $imageSlug = Str::slug($request->title, '-') . '-image';
    
            // Generate a new image name with the extension
            $newImageName = uniqid() . '-' . $imageSlug . '.' . $imageExtension;
    
            // Move the uploaded image to the public/images directory
            $request->file('image')->move(public_path('images'), $newImageName);
        }
    
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Get the file extension
            $pdfExtension = $request->file('pdf')->extension();
    
            // Generate a unique slug for the PDF
            $pdfSlug = Str::slug($request->title, '-') . '-pdf';
    
            // Generate a new PDF name with the extension
            $newPDFName = uniqid() . '-' . $pdfSlug . '.' . $pdfExtension;
    
            // Move the uploaded PDF to the public/pdfs directory
            $request->file('pdf')->move(public_path('pdfs'), $newPDFName);
        }
        
    
        // Create a new post in the database with both image_path and pdf_path
        $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'image_path' => isset($newImageName) ? $newImageName : null,
            'pdf_path' => isset($newPDFName) ? $newPDFName : null,
        ]);
    
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function create()
{
    return view('posts.create');
}

public function show($slug)
    {
        return view('posts.show')
        ->with('post',Post::where('id',$slug)->first());
    }

    public function destroy(Post $post){
        $this->authorize('delete',$post);
        $post->delete();
        return back();
    }

    public function search($name){
        
        
        return Post::where('title','like','%'.$name.'%')->get();
    }
}