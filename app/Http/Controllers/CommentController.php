<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Post $post, CommentStoreRequest $request)
    {
        return $post->comments()->create([
            'text' => $request->text,
            'user_id' => Auth::user()->id
        ]);
    }
}
