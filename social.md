# SETTINGS
         https://docs.google.com/document/d/1z4I1pxRHKZhRujvaUpiZVtnlq0Zts6LSBj_LMAYkJo0/edit?pli=1#heading=h.l0rgbwhswjr5
          https://www.youtube.com/watch?v=WahJ91Nrgn0
          https://www.youtube.com/watch?v=WahJ91Nrgn0
          https://www.youtube.com/watch?v=WahJ91Nrgn0
          https://www.youtube.com/watch?v=WcAC9Nd-FmE&list=PLLQuc_7jk__Wa8IoZ2s0J-ql_MIisndtZ&index=4
          https://www.youtube.com/watch?v=qlkS-e5ym1w&t=42s
          https://www.youtube.com/watch?v=ED8iAkPzsUE
          http://0.0.0.0:1234/register
    - Install sail via curl
    - Change the port .env
            APP_PORT=1234
    - Chage the port in docker-compose.yml
             - '${VITE_PORT:-5174}:${VITE_PORT:-5174}'
    
    - Make sure you stop the docker, 
    - Devlop on localhost
    - Execute the commad inside the container
            ./vendor/bin/sail bash
            sail@fb026289b687:/var/www/html$  -this is insside the container
            composer require laravel/breeze --dev
            php artisan breeze:install
        
    SELEECTION
        Which Breeze stack would you like to install? 
            Vue with Inertia   
        SINGLE PAGE APPLICATIION WITHH IINERTIA

    RUN DEV  VERSION
        npm run dev

# GENERATE MODELS AND MIGRATIONS
    php artisan make:model Group -m
    php artisan make:model GroupUser -m
    php artisan make:model Post -m
    php artisan make:model PostAttachment -m
    php artisan make:model PostReaction -m
    php artisan make:model Comment -m
    php artisan make:model Follower -m
    php artisan make:model Notification -m

    ADD THE COLUMN TO EXISTING TABLE
        php artisan make:migration add_column_to_users_table
    php artisan migrate

    Connect on database ,look at docker-compose.yml

     WARN[0000] The "WWWUSER " variable is not set. Defaulting to a blank string.

# GENERATE UNIQUE USERNAME ON REGISTRATION
    app/Models/User.php
    https://github.com/spatie/laravel-sluggable
        Make sure  ur inside DC 
            composer require spatie/laravel-sluggable
    - implements MustVerifyEmail in User MModel
    TRY TO REGISTER NEW USER 
        . after registerattion u will get notification
        .Use mailpit - sending fake email
            1025 Sending email
            8025 Dashboard   http://0.0.0.0:8025/

    PASSED
    
    We need to  give the possibility for user to thange into profile page
        . Add the user name 
            resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.vue
        .If vite innst running 
            sail@8a0588caac3c:/var/www/html$ npm run dev

    app/Models/User.php     
    app/Http/Controllers/ProfileController.php
     PLEASE READ THE REGEX  




# CREATE HOME PAGE UI WITH  TAILWIND CSS
    - guest  user will nott  hhave the permission to access homepage
    -  User  not authenticated will be redirected to login  page  
    -  Once user is authenticated will  see sommethig
    - Delete Dashboaard.Vue and route   routes/web.php
    - remove  dashbvoard link  in resources/js/Pages/Home.vue
    - Make HomeController inside container
        php  artisan make:controller HomeController
    - Design U
            https://tailwindui.com/?ref=top
                . Gridd https://tailwindcss.com/docs/grid-template-columns
                . Cols-spn https://tailwindcss.com/docs/grid-column#spanning-columns
            Image  rondom 
                https://picsum.photos/200
    -Create Group component
            resources/js/Components/app/GroupItem.vue
            resources/js/Components/app/GroupList.vue

    -Create Followers component
            resources/js/Components/app/FrindItem.vue
            resources/js/Components/app/FollowerList.vue

    -Create Post  component
            resources/js/Components/app/PostList.vue
            resources/js/Components/app/CreatePost.vue
                https://tailwindui.com/components/application-ui/forms/form-layouts
            resources/js/Components/app/PostItem.vue
                . https://headlessui.com/
                        https://github.com/tailwindlabs/headlessui
                        npm install @headlessui/vue@latest
            resources/js/Components/app/GroupListItems.vue
            resources/js/Components/app/GroupListItems.vue


    Delete public/build folder
    Use the  AuthenticatedLayout on Home Page
        .Scroll bar has been omitted due to herancy


# CREATE USER PROFILE PAGE UI 
        https://laravel.com/docs/10.x/routing#implicit-binding
    - Associate with the current  user
    - Open route file
      Route::get('/u/{user:username}', [ProfileController::class,'index'])->name('profile');
    - open app/Http/Controllers/ProfileController.php
    - resources/js/Pages/Profile/View.vue

# USER COVER & AVATAR  IMAGE UPLOAD 
    https://laravel.com/docs/10.x/eloquent-resources#main-content
    npm install @heroicons/vue
    https://unpkg.com/browse/@heroicons/vue@2.1.1/24/outline/
    https://inertiajs.com/forms
    - Work on resources/js/Pages/Profile/View.vue
    - Show the update button on cover
    php artisan make:resource UserResource 
    - Single action to update the  cover and avatar
    - routes/web.php

## IMPPLEMET POST CREATION PART 7
    - no attachemnt 
    - front end and backend 
    - Open app/Http/Controllers/HomeController.php
    - Open resources/js/Pages/Home.vue
    - Create  a InputTextArea.vue
    - Add logic intp CreatePost.vue
    - Define  the route
    - create Controller and model Post
            php artisan make:controller PostController --model=Post
    - Create a request Post
             php artisan make:request  StorePostRequest
             php artisan make:request  UpdatePostRequest
    - SQLSTATE[HY000]: General error: 1364 Field 'user_id' doesn't have a default value
    - Undefined property: Illuminate\Auth\AuthManager::$id
        app/Http/Controllers/PostController.php
        app/Http/Requests/StorePostRequest.php
        app/Http/Controllers/HomeController.php
        resources/js/Components/app/CreatePost.vue
        php artisan make:resource PostResource
        -resources/js/Pages/Home.vue
        -resources/js/Components/app/PostList.vue
        -resources/js/Components/app/PostItem.vue

# UPDATING AND DELETING OF POSTS
    - implemennt drop down button , 
    - Click onn edit will show up the modal pop  to be dispaly
    - https://headlessui.com/vue/menu
    -- Open resources/js/Components/app/PostItem.vue
    -- Dialogue to be in separate commponent
        https://headlessui.com/vue/dialog
            . To implemet two way binding btn PostItem and PostModal
            . To  pass the postmodal
            .Define emiit in postModal

    - Two wayys to handle the update
        Inertia  way 
        Custom http requeest ways axio
    - Uncaught Error: Ziggy error: 'post' parameter is required for route 'post.update'.

# ADD CKEDITOR DURING POST CREATION AND UPDATING
    - use chat
    - ckeditor
        https://ckeditor.com/docs/ckeditor5/latest/installation/integrations/vuejs-v3.html
            npm install --save @ckeditor/ckeditor5-vue @ckeditor/ckeditor5-build-classic
    - resources/js/app.js
    - resources/js/Components/app/PostModal.vue
    - resources/css/app.css
    - resources/js/Components/app/PostItem.vue
            provide class="ck-content-output"
    - We need to do tthe same on post creatiin
            . Display Modal 
    - Open resources/js/Pages/Home.vue
    - resources/js/Components/app/CreatePost.vue
        . change postCreating to showCreatePostModal
        . add 
             <!--    Post Modal-->
        <PostModal :post="editPost" v-model="showEditModal"/>
    - Open resources/js/Components/app/PostList.vue
        app/Http/Requests/StorePostRequest.php
    tailwind.config.js
    Attempt to read property "id" on null
        app/Http/Middleware/HandleInertiaRequests.php


#  UPLOADING ATTACHMENTS OF FRONTEND ONLY 
    - Adding the attachmentts  part to our post
    - Possibility to remove the  attacchemnt
    - Everyy attachmment has unnique name
    - Upload Multtiple file
    - Sendd the file to the sever 
    - Display them
    - Assuming we have one file ,should put in one full screen
    - How we can delete other file after uploading
    - If you close the modal ,attachment  stiil there
        /Users/developer/Documents/code/social-media-application/resources/js/Components/app/PostModal.vue
    - CClick attach file will upload file picker in PostModel
    - Open the Postitem.vue to render all the attachments
            resources/js/Components/app/PostItem.vue

         <!-- <InputTextArea v-model="form.body"  class="mb-3 w-full" />-->
     <img v-if="isImage({mime: myFile.file.type})"

    ---- SERVER SIDE ---------
    resources/js/Components/app/PostModal.vue
    - Show the progress when the  file or attachments is uploaded
    - open app/Http/Requests/StorePostRequest.php
        https://laravel.com/docs/10.x/validation#validating-files
    - open app/Http/Controllers/PostController.php
        . File to be accessiblee in the browser
    - Add  size column
        php artisan make:migration add_size_column_to_post_attachments_table 

    - How to  make file name random ? 

        php artisan make:resource PostAttachmentResource
    - We will make the image Clickable  and gallery
    - If its  oone file , we can display in full width .
        resources/js/Components/app/PostItem.vue
           grid-cols-2  lg:grid-cols-3
        resources/js/Components/app/PostModal.vue

# DELETE AND DOWNLOAD  POST ATTACHMENT
    Deleting the posst andd adding more attachemnt during post  update
    Implement tthe  downloading attachments
    Let start on editing on the post
    When creating the post we should remove the existing   attachment and add new  Attachmnet (Edit)
    Create new computed property, merge the  new attachment with existing onne
            OLD FILE
                                    [
                {
                "id": 21,
                "name": "Anxiety.pdf",
                "mime": "application/pdf",
                "size": 275812,
                "url": "/storage/attachments/35/njNH9A3Hdo73lM5WLHIEmiqU8cKh1haXZ6LUIFzO.pdf",
                "created_at": "2024-01-31T22:35:28.000000Z"
                }
                ]

            NEW FILE UPLOADDING 
            {
                "file": "[object File]",
                "url": null
                }
        - Sort byy latest
    - Downloading  the attachments
        resources/js/Components/app/PostItem.vue
    - Add web route
        Route::get('/post/download/{post}',[PostController::class,'download'])->name('post.download');
        resources/js/Components/app/PostItem.vue
        app/Http/Controllers/PostController.php

# Preview Post Attachments on Full Screen 
        
    - Preview the attachment on full screen .
    - Any post is public , or show to the  speecific grp of people
    - Make the  preview  in full screen.Open in  full screenn
    -Create  a Modal AttachmentPreviewModal.vue
        PostItem is Child
        PostList is Parent
    resources/js/Components/app/AttachmentPreviewModal.vue
    resources/js/Components/app/PostItem.vue
    resources/js/Components/app/PostList.vue
    resources/js/Components/app/PostModal.vue

# Post Attachment Validation 
    -  Upload the  error validation to the file w/c is not allowed  to be uploaded
        . dislay error on the botton of the attachment as on second
        - Open resources/js/Components/app/PostModal.vue
        - app/Http/Middleware/HandleInertiaRequests.php
        - app/Http/Requests/StorePostRequest.php
        - resources/js/Components/app/PostItem.vue
        - resources/js/Components/app/PostModal.vue

# Customize Uploaded File Size
    resources/js/Components/app/PostModal.vue
    app/Http/Controllers/PostController.php
     git checkout .
     git clean -fd
     ./vendor/bin/sail stop
     ./vendor/bin/sail  build --no-cache   
     ./vendor/bin/sail  up -d 
     ./vendor/bin/sail  bash
    sail@bdda47ce7245:/var/www/html$ npm run dev 

# Implement Reactions on Posts 
        https://axios-http.com/docs/api_intro
        https://axios-http.com/docs/interceptors
        https://stitcher.io/blog/php-enums
        https://laravel.com/docs/10.x/validation#rule-enum
    - Like  post 
    - Use axios / Fetch API
    - Install axios
         npm install -S axios
    - Create axios client
    resources/js/axiosClient.js
    resources/js/Components/app/PostItem.vue
    routes/web.php
    app/Models/Post.php
    app/Models/PostReaction.php
    app/Http/Enum/PostReactionEnum.php
    app/Http/Controllers/PostController.php
    app/Http/Resources/PostResource.php
    app/Http/Controllers/HomeController.php

# WRITING COMMENTS ON POSTS
    https://headlessui.com/vue/disclosure
    Writing the  comments on te post
    Comment  sectiion will be collapsable
    Show each post 
    Add the relationship on post model
    Add the relationship on HomeController
    Add the relationship on PostResource
    Open resources/js/Components/app/PostItem.vue
        .Click the Comment button,show the comment box/input 
        .Show the  latest post
        .show number  of comments
    - Need the name and avatar on section area
    - web route
    -  write logic in postController
    - Able to write subcomment on the post
    - Create  the commment  resource 
    - Add relaationshhip on comment model
    -Display thee comments , last  five comments on the post
    -Open  the Post model add latest5comments
    - Pass latest5comments into HomeController
    -Open the  postResource
    -Open the  postItems
    -Add Read More functionality
        . create a reusable components
    resources/js/Components/app/IndigoButton.vue
    resources/js/Components/app/ReadMoreReadLess.vue
    resources/js/Components/app/PostItem.vue
    app/Models/Post.php
    app/Models/Comment.php
    app/Http/Resources/CommentResource.php
    app/Http/Resources/PostResource.php
    app/Http/Resources/UserResource.php
    app/Http/Controllers/PostController.php
    app/Http/Controllers/HomeController.php

# UPDATE AND DELETE OF COMMENTS
    Update  the comments  by current  authenticated  user .
    DDelete  the comments 
    resources/js/Components/app/PostItem.vue
        . To create the reusable EditDeleteDropdown.vuee component (CHILD)
        . resources/js/Components/app/EditDeleteDropdown.vue (CHILD)
            @click="openEditModal" ->  @click="$emit('edit')"
           @click="deletePost" ->  @click="$emit('edit')"
        .resources/js/Components/app/PostItem.vue (PARENT)
    Visible if  i am authenticateed 
      <EditDeleteDropdown @edit="openEditModal" @delete="deletePost"/>
    - Post item has multiple comments,start  editing the comment , the input of the 1st will be canncelled.
        swecond will open
        logic
            <div v-if="editingComment && editingComment.id === comment.id" class="ml-12"></div>
    - Add nee request php artisan make:request UpdateCommentRequest
    
             axiosClient.put(route('post.comment.update', editingComment.value.id),{
                    comment:editingComment.value.comment
                })

    resources/js/Components/app/PostItem.vue
    resources/js/Components/app/EditDeleteDropdown.vue
    app/Http/Requests/UpdateCommentRequest.php
    app/Http/Controllers/PostController.php
    routes/web.php

# COMMENT LIKE / UNLIKE 
    https://laravel.com/docs/10.x/eloquent-relationships#one-to-one-polymorphic-model-structure
    Convert post reaction table to generate reaction table
    And implement the polymorphic relationship 
    From post to comment  into ractions table
    Implement the like and unlike
    Make the generic table insiide the post_reaction or we ccan create the  comment_reactiion table
    NB: Two possibilities too save the reactions of the comments
            1: Create the comment_reaction
            2: Make the post_reaction table  most generic .post_id to object

    Morphic relation can applied 
    php artisan make:migration change_post_reactions_table
        Schema::table('post_reactions', function (Blueprint $table ){
            $table->rename('reactions');
        });

    php artisan migrate:rollback --step=1
            app/Http/Enum/PostReactionEnum.php
            app/Http/Enum/ReactionEnum.php
            app/Models/PostReaction.php
             app/Models/Reaction.php
             database/migrations/2024_02_16_175828_change_post_reactions_table.php

# IMPLEMENT WRITING SUB COMMENTS
        https://github.com/lazychaser/laravel-nestedset
    Implemment the nested  comments
    Support  sub children comments
            1: Nested set model
            2: create the parent_id in commment table
     OPTION 2
    php artisan make:migration add_parent_id__to_comments --table="comments"
    parentId:{
        type:String,
        default: ''
    }

    
	modified:   app/Http/Controllers/HomeController.php
	modified:   app/Http/Controllers/PostController.php
	modified:   app/Http/Resources/CommentResource.php
	modified:   app/Models/Comment.php
	modified:   resources/js/Components/app/PostItem.vue
	mresources/js/Components/app/CommentList.vue

    Problem
         ->with([
                'comments' => function ($query) use ($userId)  {
                    $query
                        ->whereNull('parent_id')
                        ->withCount('reactions')
                        ->withCount('comments')
                        ->with([
                            'reactions' => function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            }
                        ]);
                },
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
            }])
    Improve the query of the post 
        1. SELECT * FROM post
        2. SELECT * FROM comments WHERE post_id IN (1,2,3..)
        3. SELECT COUNT(*)  from reactions
        4. SELECT COUNT(*)  from reactions
        5. SELECT *   from reactions WHHERE user_id = ? 


        private function convertCommentsIntoTree($comments, $parentId = null): array
        {
            $commentTree = [];
    
            foreach ($comments as $comment) {
                if ($comment->parent_id === $parentId) { //1st level comment
                    /*Find all comment which has parentId as $comment->id*/
                    $children = self::convertCommentsIntoTree($comments, $comment->id);
                    if ($children) {
                          $comment->childComments = $children ;
                          //$comment->setAttribute('children', $children);
                    }
                    $commentTree[] = new CommentResource($comment);
                }
            }
    
            return $commentTree;
        }

        1 First Level comment(parentId = null ) ccomment with parent id 1
            2  (Children)  -> count 2
                8 -> commentTreee[] no comments
                9 -> commentTreee[]
            3 -> count 0
            4 -> count 0
        5  First Level commennt
            6
            7

        app/Models/Comment.php
        app/Http/Controllers/HomeController.php
        app/Http/Controllers/PostController.php
        app/Http/Resources/CommentResource.php
        app/Http/Resources/PostResource.php
        resources/js/Components/app/CommentList.vue
        resources/js/Components/app/PostItem.vue
        resources/css/app.css

# LOAD MORE  ON POSTS
    Add load more functionality on te post
    Use the axios to make more request
        resources/js/Components/app/PostList.vued
        app/Http/Controllers/HomeController.php

# CREATE GROUPS
    Implent the  creating  groups
    Inite the  user inside the groups
    backend 
        controller
            php artisan make:controller GroupController
            rm -rf app/Http/Controllers/GroupController.php
            php artisan make:controller GroupController --model=Group --api --requests --resource
            php artisan make:resource GroupResource
        request class
        resources
    frondend
        group model component

    Open the resources/js/Components/app/GroupList.vue
    Create the  GroupModal
    resources/js/Components/app/GroupModal.vue
    app/Http/Controllers/GroupController.php
    app/Http/Enum/GroupUserRole.php
    app/Http/Enum/GroupUserStatus.php
    app/Http/Requests/StoreGroupRequest.php
    app/Http/Requests/UpdateGroupRequest.php
    app/Http/Resources/GroupResource.php
    app/Models/Group.php
    app/Models/GroupUser.php
    routes/web.php

# RENDER GROUPS ON HOME PAGE
    - Display the groups in the Home Page
    - With status , current authenticated user either is admin/user for this group .
    - NB: 
            WEE HAVE MOVED THE CREATE NEW GROUP INSIDE THE GROUPLISTITEMS.VUE  FROM GROUPLIST.VUE
                  resources/js/Components/app/GroupList.vue
                  resources/js/Components/app/GroupListItems.vue

    - Pass the groups in HomeController  
    - Accepts the groups via Home.vue page
        app/Http/Controllers/GroupController.php
        app/Http/Controllers/HomeController.php
        app/Http/Resources/GroupResource.php
        resources/js/Components/app/GroupItem.vue
        resources/js/Components/app/GroupList.vue
        resources/js/Components/app/GroupListItems.vue
        resources/js/Components/app/GroupModal.vue
        resources/js/Pages/Home.vue

# GROUP PROFILE PAGE
    - Create  similar url for our groups profile
        public groups  
        private  groups  
    - url will be similar to user profie http://localhost:1234/u/althea-beard
    Route::get('/g/{group:slug}', [GroupController::class,'profile'])->name('group.profile');
    - Install the Telescope debugginng tools
               https://laravel.com/docs/10.x/telescope 


    Uncaught Error: Ziggy error: 'group' parameter is required for route 'group.updateImages'.
        soln imagesForm.post(route('group.updateImages', props.group),{

            app/Http/Controllers/GroupController.php
            app/Http/Controllers/HomeController.php
            app/Http/Resources/GroupResource.php
            app/Models/Group.php
            app/Providers/AppServiceProvider.php
            composer.json
            composer.lock
             config/app.php
           resources/js/Components/app/GroupItem.vue
           resources/js/Pages/Profile/View.vue
           routes/web.php

# SEND AND ACCEPT INVITATION TO JOIN  GROUP
    -sending invitation from admin user to regular user to join to our group
    -Regular user will be able to  join our group
    - Click thhe link receive
    - Open
        resources/js/Pages/Group/View.vue
    - Must have two users in DB
        Mary - dooesnt have any group, although Mary can see the post of Admin
    - However Admin will be able to invite Mary on specific group, providing email addresss to Mary
    - Based to that, when I click yhe INVITE USERS button, I will see the Modal
            either username or email as input field
            We will show the username or emaiil doesnt exists
            If it exist we will submit, generate the tocken

    -So in group_users table , generate
            . token
            . token_expire_date (one hr from now or 24hrs from now)
            .User stataus will ppending, Mary will be receveing the notificcation to join the group
            .Mary will have the link to her email address
            . mary click the link we gonna handle this.
            . Provide te token in token_used
            .Mary click the same token , we gonna show her that the tokenn has been used.
    Start the  
        resources/js/Components/app/InviteUserModal.vue

     php artisan make:request InviteUsersRequest

    Email Sending 
        https://laravel.com/docs/10.x/notifications#mail-notifications
         php artisan make:notification InvitationInGroup
         php artisan make:notification InvitationApproved

# JOIN TO GROUPS WITH AUTO APPROVAL
    Joining the group to a regular user 
    If the  auto approval is enabled on the group level users will be able to join immediately to group
        with approval  status.
    If  the auto apprival is not enabled then the admin user will receive email notificatiion
    In later lessons we're going  to implement how to  accept those notifications approve the
        user status or reject it .
    Create notifiication
            php artisan make:notification RequestToJoinGroup
                     app/Notifications/RequestToJoinGroup.php
                 modified:   app/Http/Controllers/GroupController.php
                modified:   app/Models/Group.php
                modified:   resources/js/Components/PrimaryButton.vue
                modified:   resources/js/Pages/Group/View.vue
                modified:   routes/web.php
                modified:   social.md

        1: REGISTER A  NEW  USER
            eg xygir@mailinator.com
            eg admin1234
        2:  You will gett the notification via email (MAILPIT)
        3: Confirm the Email Address 
        4: Redirected  to  Murphy Lee
        5:try to access Murphy Lee while logged in  http://localhost:1234/g/vue-js
        6: CLICK BBUTTONN TO REQUEST TO JOIN THE GROUP
        7: open maipit
        7: LOG OUT 
        8: JOIN AS ADMIN  (admin@mailinator.com)
        10: View Groups 

# APPROVE / REJECT PENDING USERS FROM GROUP
    we're going to implement rendering all the users whhich are approved users of the  group .
    we're gooing  to also create separate tabin which w're going to render all pending users
        which request to join to  a group   but  they are not  approved or rejected yet .
    We're going to alsoo implement so that  admin users can apprpove  or reject the status of those
        users
    
    Request will be visible if v-if="isCurrentUserAdmin" 
    Admin will see both Tab 
        Posts | Users  |  Requests |  Photos
    It wont be visible for non authenticated user (http://localhost:1234/g/vue-js)
        Posts | Users  |   Photos
    User will be visible if has joined the group
        v-if="isJoinedToGroup" 
    Define thee  raltionship and use iin  GroupController
    Change  FollowingItem.vue  to  UserListItem.vue

    Send Notification
        php artisan make:notification RequestApproved


            /**Does  exists*/
        if ($groupUser) {
           $groupUser->status = GroupUserStatus::APPROVED->value;
           $groupUser->save();

           /**Send Notification to user*/
            $user = $groupUser->user;
            $user->notify(new  RequestApproved($groupUser->group, $user));


            return  back()->with('success', 'User "' . $user->name . '" was approved'  );
        }

        return  back();

        app/Notifications/RequestApproved.php
        modified:   app/Http/Controllers/GroupController.php
        modified:   app/Http/Enum/GroupUserStatus.php
        modified:   app/Http/Requests/InviteUsersRequest.php
        modified:   app/Http/Resources/UserResource.php
        modified:   app/Models/Group.php
        modified:   resources/js/Components/app/FollowingListItems.vue
        modified:   resources/js/Components/app/UserListItem.vue
        modified:   resources/js/Pages/Group/View.vue
        modified:   routes/web.php
        modified:   social.md
        http://localhost:1234/g/vue-js

# CHANGE USER ROLE INSIDE GROUP
    We're going to implemnent updating the  group details  and also changing regular roles from admin to  user 
     or vise versa from admin users
        $requests   = $group->approvedUsers()->orderBy('name')->get(); //approvedUsers
        Select the role
    - Create GroupUserResource
        php artisan make:resource  GroupUserResource
        php artisan make:notification RoleChanged

    Modification
    modified:   app/Http/Controllers/GroupController.php
	modified:   app/Models/Group.php
	modified:   resources/js/Components/app/UserListItem.vue
	modified:   resources/js/Pages/Group/View.vue
	modified:   resources/js/Pages/Profile/View.vue
	modified:   routes/web.php

    app/Http/Resources/GroupUserResource.php
	app/Notifications/RoleChanged.php
    
    Add the About  Tab on seection of the group
    Three field inside the groupModal
    Create the reusable component callled GroupForm.vue  (child)
    Attach too GroupModal(parent)
    Open Group/View.vue in About Tab

    Modification 
    modified:   app/Http/Controllers/GroupController.php
	modified:   app/Http/Requests/UpdateGroupRequest.php
	modified:   resources/js/Components/app/GroupModal.vue
	modified:   resources/js/Pages/Group/View.vue
	modified:   routes/web.php
	modified:   social.md

    resources/js/Components/app/GroupForm.vue

# CREATE AND LOAD GROUP POST
    We're going  to implement  creating and  renderiing posts inside  the groups profile page.
    Load post to a group in GroupController
        . Refactor the 
                 Post::query()  /**SELECT * FROM post*/
        /** SELECT COUNT(*)  from reactions**/
        ->withCount('reactions')
            ->with([
                /** SELECT * FROM comments WHERE post_id IN (1,2,3..)**/
                'comments' => function ($query)  {
                    /** SELECT COUNT(*)  from reactions**/
                    $query ->withCount('reactions');
                },

                'reactions' => function ($query) use ($userId) {
                    /**SELECT *   from reactions WHERE user_id = ? */
                    $query->where('user_id', $userId);
                }]);

        $posts = Post::query()  /**SELECT * FROM post*/
        /** SELECT COUNT(*)  from reactions**/
        ->withCount('reactions')
            ->with([
                /** SELECT * FROM comments WHERE post_id IN (1,2,3..)**/
                'comments' => function ($query)  {
                    /** SELECT COUNT(*)  from reactions**/
                    $query ->withCount('reactions');
                },

                'reactions' => function ($query) use ($userId) {
                    /**SELECT *   from reactions WHERE user_id = ? (current user)*/
                    $query->where('user_id', $userId);
                }])
                ->where('group_id', $group->id)
                 ->latest()
                ->paginate(10);

    - Post will displayed inn Group/View Tab
    - chhange anchor tag innto Lin iin PostUserHeader
        
        modified:   app/Http/Controllers/GroupController.php
        modified:   app/Http/Controllers/HomeController.php
        modified:   app/Http/Controllers/PostController.php
        modified:   app/Http/Requests/StorePostRequest.php
        modified:   app/Http/Resources/CommentResource.php
        modified:   app/Http/Resources/GroupUserResource.php
        modified:   app/Http/Resources/UserResource.php
        modified:   app/Models/Group.php
        modified:   app/Models/Post.php
        modified:   app/Notifications/RequestApproved.php
        modified:   resources/js/Components/app/CommentList.vue
        modified:   resources/js/Components/app/CreatePost.vue
        modified:   resources/js/Components/app/PostItem.vue
        modified:   resources/js/Components/app/PostList.vue
        modified:   resources/js/Components/app/PostModal.vue
        modified:   resources/js/Components/app/PostUserHeader.vue
        modified:   resources/js/Components/app/UserListItem.vue
        modified:   resources/js/Pages/Group/View.vue


# DELETING  POSTS AND COMMENTS BY ADMIN USERS
    W're going to iimplement functionality that admin users willl be able to delete post by  regular users
     inside the group and we're going to also do that  the owner of the post will be able to delete
     comments made by  regular users on their own posts if they decide to delete that.
      
    - Admin will be able to delete the post made by other user
    - Admin will be able to delete the comments made by other user
    -  Create a notification
             php artisan make:notification CommentDeleted
             php artisan make:notification PostDeleted
            
        app/Notifications/CommentDeleted.php
	    app/Notifications/PostDeleted.php

        modified:   app/Http/Controllers/PostController.php
        modified:   app/Http/Resources/PostResource.php
        modified:   app/Models/Comment.php
        modified:   app/Models/Post.php
        modified:   resources/js/Components/app/CommentList.vue
        modified:   resources/js/Components/app/EditDeleteDropdown.vue
        modified:   resources/js/Components/app/PostItem.vue
        modified:   social.md

# REMOVE USERS FROM GROUPS
    Allow admin users to remove another  users from the group .
    php artisan make:notification UserRemovedFromGroup
        app/Notifications/UserRemovedFromGroup.php

        modified:   app/Http/Controllers/GroupController.php
        modified:   resources/js/Components/app/UserListItem.vue
        modified:   resources/js/Pages/Group/View.vue
        modified:   routes/web.php
        modified:   social.md

# SEND EMAIL NOTIFICATIONS ON VARIOUS ACTIONS
    We're gooing to implement functionality to send mail notifications 
            When post is created
            When comment is created
            When post is liked
            When comment is liked to the corresppondding users.
        php artisan make:notification PostCreated
        php artisan make:notification CommentCreated
        php artisan make:notification ReactionAddedOnPost
        php artisan make:notification ReactionAddedOnComment

# CREATE DEDICATED POST PAGE 
     We're gooing to implement dedicated post page , that's goingg to be usefuul if you want to
      share thhe specific post to somebody else   now eachh ppost willl have its own URL you can
      copy that and sharing that url,pposted to another social media.

    eg: http://localhost:1234/post/3

         modified:   app/Http/Controllers/PostController.php
        modified:   app/Notifications/CommentCreated.php
        modified:   app/Notifications/CommentDeleted.php
        modified:   app/Notifications/PostCreated.php
        modified:   app/Notifications/ReactionAddedOnComment.php
        modified:   app/Notifications/ReactionAddedOnPost.php
        modified:   resources/js/Components/app/EditDeleteDropdown.vue
        modified:   resources/js/Components/app/PostList.vue
        modified:   resources/js/Pages/Post/View.vue
        modified:   routes/web.php

#  DISPLAY ABOUT GROUP TAB ON GROUP PROFILE PAGE
    We're gooing to implement rendering about  group details  on the groups's profile page.For this
        we're going  to add  a new  tab about  grou tab.

    v-if="isCurrentUserAdmin" remove this 

        modified:   app/Http/Requests/StoreGroupRequest.php
        modified:   app/Http/Requests/UpdateGroupRequest.php
        modified:   resources/js/Pages/Group/View.vue
        modified:   social.md

# FOLLOW USER , SHOW POSTS , FOLLOWERS AND FOLLOWING 
    This part  iis goinng to be pretty interesting , We're going to implement follow and  unfollow functionaity.
    We're going to display post for a specific user on the user's profile page and we're going to show
    all the followers and followings of a specific user on its profile page 
     CLICK ->FOLLOW USER -> MAKE REQUEST ->BACKEND SIDE->CREATE NEW RECORD INSIDE THE  FOLLOW TABLE.
    SEND AND EEMAIL TO A USER ->CURRENT  JOHN SMITH HAS FOLLOW U
    php artisan make:notification FollowUser

    Who I am following ?
    Who is following me?
    Should be visible to other users
    Might be large array
          1: complex way is create a separate page for posts, Followers, Followings and Photos ,Once you click one 
            of them ,the url change

          2:  Create the child layout 

# SHOW ONLY RELEVANT POSTS ON HOME PAGE
            
    We're going to implement rendering  oonly relevant posts on the homepage timeline of the user.
    We're going to show only posts which are  placed  in the same group I  am part of OR which are  posted  by the users
    I am following.
        
        TIMELINE ON HOME PAGE.
                I don't want to see anyone on my time line if ,idont follow them.

    
    $followers = User::query()
                        ->select('users.*')
                        ->join('followers AS f', 'f.follower_id','users.id')
                        ->where('f.user_id', $user->id) //user is Mary $user->id
                        ->get();

        $followings = User::query()
            ->select('users.*')
            ->join('followers AS f', 'f.user_id', 'users.id')
            ->where('f.follower_id', $user->id)
            ->get();

    CHANGE TO 
            $followers = $user->followers;

            $followings = $user->followings;

         ->line('Thank you for using our application!');
            ->line('New post was added by user "' . $this->group->slug . '"')
            ->action('View Post', url(route('post.view', $this->post->id)))
  

            modified:   app/Http/Controllers/HomeController.php
            modified:   app/Http/Controllers/PostController.php
            modified:   app/Http/Controllers/ProfileController.php
            modified:   app/Models/User.php
            modified:   app/Notifications/PostCreated.php
            modified:   resources/js/Components/app/FollowingList.vue
            modified:   resources/js/Components/app/FollowingListItems.vue
            modified:   resources/js/Components/app/GroupItem.vue
            modified:   resources/js/Pages/Home.vue
            modified:   resources/js/Pages/Profile/View.vue
        EG Mary I  am not following to Mary , I should not see Mary Post on my  Timeline

            SELECT f.follower_id , gu.group_id, posts.* FROM posts
                LEFT JOIN followers f on posts.user_id = f.user_id and f.follower_id = :userId
                LEFT JOIN group_users gu  on gu.user_id = :userId and gu.group_id =  posts.group_id and gu.status = 'approved'
                
                WHERE posts.deleted_at is null
                AND (f.follower_id IS NOT NULL OR gu.group_id IS NOT NULL )  #OR posts.user_id = :userId
                AND  posts.user_id  != :userId


# IMPLEMENT PHOTOS TAB 
    Display photos in Group profile page and in user profile page
     We're going to implement photos to for the group profie page as well as for the user profile page 
     We're  going to render all the photos that  is uploaded in  group or in the  profile page w/c the 
        possibility to expand the photos  and  see on full screen aand navigate btn left  and previous 
        buttons .W're going to use the  same component what we hhave already  used in the postt attachments area.
    
    QUERY
        select  * from post_attachments
            WHERE  mime like 'image/%'
            AND created_by

        modified:   app/Http/Controllers/GroupController.php
        modified:   app/Http/Controllers/ProfileController.php
        modified:   resources/js/Components/app/PostItem.vue
        modified:   resources/js/Pages/Group/View.vue
        modified:   resources/js/Pages/Profile/View.vue
         resources/js/Pages/Profile/TabPhotos.vue

# GENERATE POST CONTENT  WITH CHAT GPT API
    We're going to use open AI API and implement  generation post content with help of AI lile thhe
        CHAT GPT API ,you can provide the   prompt hit this AI button  and it wiill render and generate
    post content for me .

    https://platform.openai.com/docs/overview
    https://github.com/openai-php/laravel
    sh: 1: xdg-open: not found

    There aare no commands defined  in the "openai" namespace
            modified:   .env.example
            modified:   app/Http/Controllers/PostController.php
            modified:   composer.json
            modified:   composer.lock
            modified:   resources/js/Components/app/PostModal.vue
            modified:   routes/web.php
            config/openai.php
	        resources/js/plugins/

# GLOBAL SEARCH FOR USERS , GROUPS , POSTS
    We're going  to add global search bar and implement searching for users ,groups, posts from the homepage.
    
    I have build social mediaa website  with laravel andd record the entire projectt . There are 40+ hours of video  recorded.
    I plan yo pubblish the entire playlist on my youtube  channel .

    Buuild UI
    Make controlller
            php  artisan make:controller SearchController
    Create these search components
            app/Http/Controllers/SearchController.php
            resources/js/Pages/Search.vue

            modified:   resources/js/Layouts/AuthenticatedLayout.vue
            modified:   resources/js/Pages/Profile/TabPhotos.vue
            modified:   routes/web.php

# SEARCH FOR POSTS WITH HASH TAGS
    We're going to implement  rendering hashtags properly with a different color and implement  the functionality  to search posts
        with hashtag
        modified:   app/Http/Requests/StorePostRequest.php
        modified:   resources/css/app.css
        modified:   resources/js/Components/app/PostItem.vue
        modified:   resources/js/Layouts/AuthenticatedLayout.vue
        modified:   resources/js/Pages/Search.vue

# ATTACHMENT VIDEO PREVIEW
    We're going to implement video preview  on  the post attachments ,  we're going to  do preview for MP4 files at the
        moment ONLY
        
            PostAttachments.vue
            modified:   resources/js/Components/app/AttachmentPreviewModal.vue
            modified:   resources/js/Components/app/PostAttachments.vue
            modified:   resources/js/Helpers/helpers.js

# IMPLEMENT  URL PREVIEW 
    We're going to implement URL preview like when you post the URL it's going to fetch the information  of the URL and
        display  that nice preview .




