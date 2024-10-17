<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Image;

class PostController extends Controller
{
    // Display a listing of the posts
    public function index(){
        $posts = Post::with('image')->get();
        return view('posts.index', compact('posts'));
    }

    // Show the form for creating a new post
    public function create(){
        return view('posts.create');
    }

    // Store a newly created post in storage
    public function store(Request $request){
        // Validate incoming data
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate each image
        ]);

        // Create the post
        $post = Post::create($request->only(['title', 'content']));

        // Handle image upload
        if($request->hasFile('images')){
            foreach($request->file('images') as $file){
                // Define the path where you want to store the images
                $destinationPath = public_path('images'); // Set destination to public/images

                // Generate a unique filename to avoid overwriting
                $filename = time() . '_' . $file->getClientOriginalName();

                // Move the uploaded file to the specified path
                $file->move($destinationPath, $filename); // Use move() instead of store()

                // Store the path in the database
                Image::create([
                    'image' => 'image/' . $filename, // Store relative path for the database
                    'post_id' => $post->id,
                ]);
            }
        }
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    // Display the specified post
    public function show($id){
        $post = Post::with('images')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    // Show the form for editing the specified post
    public function edit($id){
        $post = Post::with('images')->findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id){
        // Validate incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'images.*' => 'image|mins:jpeg,png,jpg,gif,svg|max:2048', // validate each image
        ]);

        // Find the post and update it
        $post = Post::findOrFail($id);
        $post->update($request->only(['title', 'content']));

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old images
            foreach ($post->images as $image) {
                $imagePath = public_path($image->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the file from the server
                }
                $image->delete(); // Remove the image record from the database
            }

            // Upload and save new images
            foreach ($request->file('images') as $file) {
                $destinationPath = public_path('images'); // Store in public/images
                $filename = time() . '_' . $file->getClientOriginalName(); // Unique filename
                $file->move($destinationPath, $filename); // Move to public/images

                // Store the path in the database
                Image::create([
                    'image' => 'images/' . $filename, // Save relative path
                    'post_id' => $post->id,
                ]);
            }
        }
        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    // Remove the specified post from storage
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->images()->delete(); // Delete related images
        $post->delete(); // Delete the post

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
