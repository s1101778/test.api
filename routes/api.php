<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
php artisan passport:install

php artisan make:model Name --migration

php artisan make:migration update_flights_table

php artisan migrate
php artisan migrate:rollback

php artisan route:list 查看可用
php artisan queue:clear
sudo supervisorctl restart all
supervisorctl reread
supervisorctl update

php artisan schedule:list 查看排程
*/

Route::prefix('auth')->group(function(){
    Route::get('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::middleware('auth:api')->group(function(){
        Route::post('logout',[AuthController::class,'logout']);
        Route::get('user',[AuthController::class,'userInfo']);
    });
    Route::prefix('reset_password')->group(function(){
        Route::post('send',[ForgotPasswordController::class,'send_reset_mail']);
        Route::post('check',[ForgotPasswordController::class,'token_check']);
        Route::post('reset',[ForgotPasswordController::class,'check_reset_mail']);
    });
});

Route::prefix('forum')->group(function(){
    Route::middleware('auth:api')->group(function(){
        Route::post('post',[PostController::class,'post']);
        Route::post('like_post',[PostController::class,'like_post']);
        Route::post('del_post',[PostController::class,'del_post']);
        Route::post('comment',[CommentController::class,'comment']);
        Route::post('like_comment',[CommentController::class,'like_comment']);
        Route::post('del_comment',[CommentController::class,'del_comment']);
    });
    Route::post('get_post',[PostController::class,'get_post']);
    Route::post('get_comment',[CommentController::class,'get_comment']);
});

Route::get('sendmail',[PostController::class,'post']);




Route::prefix('test')->group(function(){
    Route::get('test1',[TestController::class,'test1']);
    Route::get('test2',[TestController::class,'test2']);
    Route::get('test3',[TestController::class,'test3']);
    Route::get('test4',[TestController::class,'test4']);
});




