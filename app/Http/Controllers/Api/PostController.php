<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
   // Create Blog Post
   public function store(Request $request)
   {
       $validated = $request->validate([
           'title' => 'required|string|max:255',
           'content' => 'required|string',
           'category' => 'required|string',
           'tags' => 'required|array',
       ]);

       $post = Post::create($validated);
       return response()->json($post, 201);
   }

   // Get All Blog Posts
   public function index(Request $request)
   {
       $term = $request->query('term');
       $query = Post::query();

       if ($term) {
           $query->where(function($q) use ($term) {
               $q->where('title', 'like', "%{$term}%")
                 ->orWhere('content', 'like', "%{$term}%")
                 ->orWhere('category', 'like', "%{$term}%");
           });
       }

       return response()->json($query->get());
   }

   // Get Single Blog Post
   public function show($id)
   {
       $post = Post::find($id);
       if (!$post) {
           return response()->json(['message' => 'Post not found'], 404);
       }
       return response()->json($post);
   }

   // Update Blog Post
   public function update(Request $request, $id)
   {
       $post = Post::find($id);
       if (!$post) {
           return response()->json(['message' => 'Post not found'], 404);
       }

       $validated = $request->validate([
           'title' => 'required|string|max:255',
           'content' => 'required|string',
           'category' => 'required|string',
           'tags' => 'required|array',
       ]);

       $post->update($validated);
       return response()->json($post);
   }

   // Delete Blog Post
   public function destroy($id)
   {
       $post = Post::find($id);
       if (!$post) {
           return response()->json(['message' => 'Post not found'], 404);
       }

       $post->delete();
       return response()->json(null, 204);
   }
}
