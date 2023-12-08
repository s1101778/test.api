<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use App\Models\UserLike;

class CommentController extends Controller
{
    public function Comment(Request $data)
    {
        $validator=Validator::make($data->all(),[
            'post_id'=>'required|exists:posts,id',
            'parent_comment_id'=>'exists:comments,id',
            'content'=>'required',
            'mention'=>'required|starts_with:"["|ends_with:"]"',
        ],[
            'required'=>'欄位沒有填寫完整!',
            'post_id.exists'=>'貼文不存在',
            'parent_comment_id.exists'=>'父comment不存在',
            'mention.starts_wih'=>'mention格式不存在',
            'mention.ends_with'=>'mention格式不正確',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->first()],401);
        }else{
            if(Comment::find($data->comment_id)){
                Comment::find($data->comment_id)->update([
                    'mention'=>$data->mention,
                    'content'=>$data->content,
                ]);
                return response()->json(['success'=>'成功更新留言'],200);
            }else{
                Comment::create([
                    'parent_comment_id'=>$data->parent_comment_id,
                    'user_id'=>Auth::user()->id,
                    'post_id'=>$data->post_id,
                    'mention'=>$data->mention,
                    'content'=>$data->content,
                ]);
                Post::find($data->post_id)->increment('comments_count');
                return response()->json(['success'=>'成功發布留言'],200);
            }
        }
    }
    public function del_comment(Request $data)
    {
        $validator=Validator::make($data->all(),[
            'comment_id'=>'required|exists:comments,id',
        ],[
            'required'=>'欄位沒有填寫完整!',
            'comment_id.exists'=>'留言不存在',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->first()],401);
        }else{
            $Comment=Comment::where([
                'user_id'=>Auth::user()->id,
                'id'=>$data->comment_id
            ]);

            $post_id=$Comment->first()->Post->id;

            $Children_Comment=Comment::where([
                'parent_comment_id'=>$data->comment_id
            ]);
            $Children_Comment_count=$Children_Comment->count();

            $Comment=$Comment->delete();
            if($Comment==1){
                Post::find($post_id)->decrement('comments_count',1+$Children_Comment_count);
                return response()->json(['success'=>'成功刪除留言'],200);
            }
        }
    }
    public function like_comment(Request $data)
    {
        if(Comment::find($data->comment_id)){
            $like=$data->like;
            if($like==1){
                if(UserLike::where([
                    'user_id'=>Auth::user()->id,
                    'comment_id'=>$data->comment_id
                ])->doesntExist()){
                    UserLike::create([
                        'user_id'=>Auth::user()->id,
                        'comment_id'=>$data->comment_id,
                    ]);
                    Comment::find($data->comment_id)->increment('likes');
                }
            }else if($like==0){
                if(Comment::find($data->comment_id)->likes>0){
                    $UserLike=UserLike::where([
                        'user_id'=>Auth::user()->id,
                        'comment_id'=>$data->comment_id
                    ])->delete();
                    if($UserLike==1){
                        Comment::find($data->comment_id)->decrement('likes');
                    }
                }
            }
            return response()->json(['success'=>'更新喜歡狀態成功'],200);
        }
    }
    public function get_comment(Request $data)
    {
        $page=$data->page;
        $page=0;
        $post_id=$data->post_id;
        $comments=Post::find($post_id)->Comment;

        $comments=$comments->map(function($item,$key){
            return collect([
                'id'=>$item['id'],
                'user_id'=>$item['user_id'],
                'post_id'=>$item['post_id'],
                'user_name'=>$item->User->name,
                'content'=>$item['content'],
                'likes'=>$item['likes'],
                'created_at'=>$item['created_at']->format('Y/m/d H:i:s'),
                'updated_at'=>$item['updated_at']->format('Y/m/d H:i:s'),
            ]);
        });
        $comments=$comments->sortByDesc('created_at')->sortByDesc('likes');

        $comment=$comments->filter()->values();

        //$comments=$comments->slice($page*5,$page+5)->values();
        return response()->json(['success'=>$comments],200);
    }
}
