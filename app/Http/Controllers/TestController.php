<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserLike;
#use App\Models\StockName;
#use App\Models\StockCalculate;
#use App\Models\StockCalculateGroup;
#use App\Models\TestStock;
#use App\Models\StockData;
#use App\Models\StockSpecialKindDetail;

class TestController extends Controller
{

    public function test1()
    {
        $UserLike=UserLike::where([
            'user_id'=>1,
            'post_id'=>1
        ])->delete();
        return response()->json(['success'=>$UserLike],200);
    }
    public function test2()
    {
        return response()->json(['success'=>'success'],200);
    }
    public function test3()
    {
        return response()->json(['success'=>'success'],200);
    }
    public function test4()
    {
        return response()->json(['success'=>'success'],200);
    }
}