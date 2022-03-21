<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostWithCommentsResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['userPosts', 'store', 'update', 'destroy', 'index']);
    }

    public function userPosts()
    {
        return PostResource::collection(Auth::user()->posts);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = Auth::user();
        if (isset($user)) {
            if ($user->IsAdmin()) {
                return PostResource::collection(Post::all());
            } else {
                if ($user->IsMaster()){
                    return PostResource::collection(Post::all()->where("sp", '=', "СП-$user->IsMaster"));
                }

                return PostResource::collection(Auth::user()->posts);
            }
        } return PostResource::collection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
            $data = $request->only(['text', 'sp',"cab"]);

            return Auth::user()->posts()->create($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return PostWithCommentsResource
     */
    public function show(Post $post)
    {
        return new PostWithCommentsResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post,Request $request)
    {
        $user = Auth::user();
        if (isset($user)) {
            if ($user->IsMaster()) {
                if ($post->master_id == null) {
                    $post->update($request->only('status'));
                    $post->update(['master_id' => $user->id()]);
                    return response($post, 202);
                } else {
                    if ($post->master_id == $user->id()){
                        $post->update($request->only('status'));
                        return response($post, 202);
                    } else {
                        return response()->json([
                            'message' => "Уже принято",
                        ], 422);
                    }
                }
            } else {
                return response()->json([
                    'message' => "No permission",
                ], 422);
            }
        }
        return response($user,422);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = Auth::user();
        if (isset($user)) {
            if ($user->IsAdmin() or $user->IsCreator($post)) {
                $post->delete();
                return response($post, 202);
            } else {
                return response()->json([
                    'message' => "No permission",
                ], 422);
            }
        } return response($user,400);
    }
}
