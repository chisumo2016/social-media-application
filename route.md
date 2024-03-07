   
    /**Posts Route*/
    Route::get('/post/{post}', [PostController::class, 'view'])->name('post.view');
    Route::post('/post', [PostController::class, 'store'])->name('post.create');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::get('/post/download/{attachment}',[PostController::class,'downloadAttachment'])->name('post.download');

    Route::post('/post/{post}/reaction',[PostController::class,'postReaction'])->name('post.reaction');
    Route::post('/post/{post}/comment',[PostController::class,'createComment'])->name('post.comment.create');





            /**Comments*8/

    Route::delete('/comment/{comment}',[PostController::class,'deleteComment'])->name('comment.delete');
    Route::put('/comment/{comment}',[PostController::class,'updateComment'])->name('comment.update');
    Route::post('/comment/{comment}/reaction',[PostController::class,'commentReaction'])->name('comment.reaction');


        /**Groups**/

    Route::post('/group', [GroupController::class,'store'])->name('group.create');

    Route::post('/group/update-images/{group:slug}', [GroupController::class, 'updateImage'])->name('group.updateImages');
    Route::post('/group/invite/{group:slug}',        [GroupController::class, 'inviteUser'])->name('group.inviteUser');
    Route::post('/group/join/{group:slug}',        [GroupController::class, 'join'])->name('group.join');
    Route::post('/group/approve-request/{group:slug}',        [GroupController::class, 'approveRequest'])->name('group.approveRequest');
    Route::post('/group/change-role/{group:slug}',        [GroupController::class, 'changeRole'])->name('group.changeRole');
    Route::put('/group/{group:slug}', [GroupController::class,'update'])->name('group.update');

    Route::delete('/remove-user/{group:slug}',        [GroupController::class, 'removeUser'])->name('group.removeUser');
