<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UvaTopic;
use App\Models\Post;
use App\Models\UserLike;

class PostController extends Controller
{
    public function post(Request $data)
    {
        $validator=Validator::make($data->all(),[
            'serial'=>'required|exists:uva_topics,serial',
            'video_url'=>'required|url',
            'content'=>'required',
        ],[
            'required'=>'欄位沒有填寫完整!',
            'video_url.url'=>'請放入影片網址',
            'serial.exists'=>'題目編號不存在',
        ]);
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->first()],401);
        }else{
            if(Post::find($data->post_id)){
                Post::find($data->post_id)->update([
                    'uva_topic_id'=>UvaTopic::get_uva_topic_id($data->serial),
                    'video_url'=>$data->video_url,
                    'content'=>$data->content,
                ]);
                return response()->json(['success'=>'成功更新貼文'],200);
            }else{
                Post::create([
                    'user_id'=>Auth::user()->id,
                    'uva_topic_id'=>UvaTopic::get_uva_topic_id($data->serial),
                    'video_url'=>$data->video_url,
                    'content'=>$data->content,
                ]);
                return response()->json(['success'=>'成功創立貼文'],200);
            }
        }
    }
    public function like_post(Request $data)
    {
        if(Post::find($data->post_id)){
            $like=$data->like;
            if($like==1){
                if(UserLike::where([
                    'user_id'=>Auth::user()->id,
                    'post_id'=>$data->post_id,
                ])->doesntExist()){
                    UserLike::create([
                        'user_id'=>Auth::user()->id,
                        'post_id'=>$data->post_id,
                    ]);
                    Post::find($data->post_id)->increment('likes');
                }
            }else if($like==0){
                if(Post::find($data->post_id)->likes>0){
                    $UserLike=UserLike::where([
                        'user_id'=>Auth::user()->id,
                        'post_id'=>$data->post_id
                    ])->delete();
                    if($UserLike==1){
                        Post::find($data->post_id)->decrement('likes');
                    }
                }
            }
            return response()->json(['success'=>'更新喜歡狀態成功'],200);
        }
    }
    public function get_posts(Request $data)
    {
        $star=collect(json_decode($data->star,true));
        $sort=$data->sort;
        $page=$data->page;
        $page=0;
        $posts=Post::all();

        $posts=$posts->map(function($item,$key) use ($star){
            if($star->contains($item->UvaTopic->star)||count($star)==0){
                return collect([
                    'id'=>$item['id'],
                    'user_id'=>$item['user_id'],
                    'user_name'=>$item->User->name,
                    'uva_topic'=>$item->UvaTopic,
                    'video_url'=>$item['video_url'],
                    'content'=>$item['content'],
                    'likes'=>$item['likes'],
                    'comments_count'=>$item['comments_count'],
                    'created_at'=>$item['created_at']->format('Y/m/d H:i:s'),
                    'update_at'=>$item['update_at']->format('Y/m/d H:i:s'),
                ]);
            }
        });
        switch ($sort){
            case 0:
                $posts=$posts->sortByDesc('created_at');
                break;
            case 1:
                $posts=$posts->sortBy('created_at');
                break;
            case 2:
                $posts=$posts->sortByDesc('comments_count');
            case 3:
                $posts=$posts->sortBy('comments_count');
                break;
            case 4:
                $posts=$posts->sortByDesc('likes');
                break;
            case 5:
                $posts=$posts->sortBy('likes');
            default:
                $posts=$posts->sortByDesc('created_at');
                break;
        }
        $posts=$post->filter()->values();

        //$posts=$posts->slice($page*5,$page+5)->values();
        return response()->json(['success'=>$posts],200);
    }
}
