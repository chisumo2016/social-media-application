<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/u/{user:username}', [ProfileController::class,'index'])->name('profile');

Route::get('/g/{group:slug}', [GroupController::class,'profile'])->name('group.profile');

Route::get('/group/approve-invitation/{token}',        [GroupController::class, 'approveInvitation'])->name('group.approveInvitation');

Route::middleware('auth')->group(function () {
            /**Profile Route*/
    Route::post('/profile/update-images', [ProfileController::class, 'updateImage'])->name('profile.updateImages');
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**Posts Route*/
    Route::prefix('/post')->group(function () {
        Route::get('{post}', [PostController::class, 'view'])->name('post.view');
        Route::post('/', [PostController::class, 'store'])->name('post.create');
        Route::put('/{post}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('post.destroy');

        Route::get('/download/{attachment}',[PostController::class,'downloadAttachment'])->name('post.download');

        Route::post('/{post}/reaction',[PostController::class,'postReaction'])->name('post.reaction');
        Route::post('/{post}/comment',[PostController::class,'createComment'])->name('post.comment.create');

        Route::post('/ai-post', [PostController::class, 'aiPostContent'])->name('post.aiContent');
        Route::post('/fetch-url-preview', [PostController::class, 'fetchUrlPreview'])->name('post.fetchUrlPreview');

        Route::post('/{post}/pin', [PostController::class, 'pinUnpin'])->name('post.pinUnpin');
    });



    /**Comments Route*/
    Route::prefix('/comment')->group(function (){

        Route::delete('/{comment}',[PostController::class,'deleteComment'])->name('comment.delete');
        Route::put('/{comment}',[PostController::class,'updateComment'])->name('comment.update');
        Route::post('/{comment}/reaction',[PostController::class,'commentReaction'])->name('comment.reaction');
    });


    /**Groups Route*/
    Route::prefix('/group')->group(function (){
        Route::post('/', [GroupController::class,'store'])->name('group.create');

        Route::post('/update-images/{group:slug}', [GroupController::class, 'updateImage'])->name('group.updateImages');
        Route::post('/invite/{group:slug}',        [GroupController::class, 'inviteUser'])->name('group.inviteUser');
        Route::post('/join/{group:slug}',        [GroupController::class, 'join'])->name('group.join');
        Route::post('/approve-request/{group:slug}',        [GroupController::class, 'approveRequest'])->name('group.approveRequest');
        Route::post('/change-role/{group:slug}',        [GroupController::class, 'changeRole'])->name('group.changeRole');
        Route::put('/{group:slug}', [GroupController::class,'update'])->name('group.update');

        Route::delete('/remove-user/{group:slug}',        [GroupController::class, 'removeUser'])->name('group.removeUser');
    });

    Route::post('/user/follow/{user}', [UserController::class, 'follow'])->name('user.follow');

    Route::get('/search/{search?}',    [SearchController::class, 'search'])->name('search');

   // Route::post('/{post}/pin', [\App\Http\Controllers\PinController::class, 'pinUnpin'])->name('post.pinUnpin');


});

require __DIR__.'/auth.php';

