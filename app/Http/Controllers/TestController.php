<?php

namespace App\Http\Controllers;


use App\Models\StockName;
use App\Models\StockCalculate;
use App\Models\StockCalculateGroup;
use App\Models\TestStock;
use App\Models\StockData;
use App\Models\StockSpecialKindDetail;

class TestController extends Controller
{

    public function test1()
    {
        #return response()->json(['success'=>$BB_bulletin],200);
        return "test1";
    }
    public function test2()
    {
        return response()->json(['success'=>$aa],200);
    }
    public function test3()
    {
        return response()->json(['A_B_same_category_up'=>$B_category->flatten(1)],200);
    }
    public function test4()
    {
        return response()->json(['success'=>''],200);
    }
}