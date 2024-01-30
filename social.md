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










