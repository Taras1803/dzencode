<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::index();
        $count = count($comments);
        return view('welcome', compact('comments', 'count'));
    }

    public function addComment(Request $request)
    {
        $json = Comment::add($request);
        return response()->json($json);
    }

}
