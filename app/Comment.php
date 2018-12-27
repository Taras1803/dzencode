<?php
/**
 * Created by PhpStorm.
 * User: Taras
 * Date: 25.12.2018
 * Time: 19:35
 */

namespace App;
use Validator;
use Composer\DependencyResolver\Request;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comments";

    static function index()
    {
        $comments = Comment::where(['parent_id' => 0])->get();
        foreach ($comments as $key => $comment) {
            $children = Comment::where(['parent_id' => $comment['id']])->get();
            if (count($children) > 0) {
                $comments[$key]['children'] = $children;
            }
        }
        return $comments;
    }


    static function add($request)
    {
        $json = [];
        $rules = [
            'user_name' => 'required|min:6|max:20',
            'email' => 'required|email',
            'comment' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $json['error'] = 1;
            $json['text'] = $validator->errors()->first();
        } else {
            $json['error'] = 0;
            $json['text'] = 'Comment add success';
            $json['action'] = 'clear_and_show_text';
            $json['child'] = $request->parent_id;
            $json['user_name'] = $request->user_name;
            $json['comment'] = $request->comment;
            $comment = new Comment;
            $comment->user_name = $request->user_name;
            $comment->email = $request->email;
            $comment->comment = $request->comment;
            $comment->ip = $_SERVER['REMOTE_ADDR'];
            $comment->agent = Helper::getUserAgent();
            $comment->parent_id = $request->parent_id;
            $comment->page_url = $request->page_url;
            if (\Auth::check()) {
                $comment->user_id = \Auth::id();
            } else {
                $comment->user_id = 0;
            }
            $comment->save();
        }
        return $json;
    }
}