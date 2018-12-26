<?php
/**
 * Created by PhpStorm.
 * User: Taras
 * Date: 25.12.2018
 * Time: 19:35
 */

namespace App;

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
//        dd($request);

        $comment = new Comment;
        $comment->user_name = $request->user_name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->ip = $_SERVER['REMOTE_ADDR'];
        $comment->agent = Helper::getUserAgent();
        if($request->parent_id != 0){
            $comment->parent_id = $request->parent_id;
        }
        $comment->page_url= $request->page_url;
        if (\Auth::check()) {
            $comment->user_id = \Auth::id();
        }else{
            $comment->user_id = 0;
        }
        $comment->save();
    }
}