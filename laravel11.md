    web application
    Eco system
    Reactive Application
    Laravel Herd https://herd.laravel.com/ - Server
    Laravel pulse  https://pulse.laravel.com/  like a dr
    FrankenPHP   - Server
    Laravel Vorge
    Laravel Vapor
    
        ROUTE
        -  Less file 
        - No extra api
        php artisan install:api
     CHANNEL ROUTE
        - REMOVED
        php artisan install:broadcastiing

    CONSOLE ROUTE FIILE
        Schedule::call(functiion (){

        })->everySecond();
    
    php artisaan make:command 
        sendEmails

    PROVIDES
        Automatically with framewroks
        Only  1 service provide (AppServiceProvider.php)  not  5 as before  
        Rest provider  are  automatically  discovery
        Event  is  the same
    php artisan make:provider
        InvoicingServiceProvider

    php artisaan make:event  eg PodcastProcessed
    php artisaan make:listnner  eg SendPodcastNotificaton
    bootstrap/cache/app.php
    HEALTH 


    MIDDLEWARE
        No middlware in laravel 11  Http/Controller
    php artisana make:middleware EnsureSubscriber

    CLASS
        php artissan make:class -i

    MIGRATION
    Databaase driver and  local for development
        Only three files eg users , cache and jobs
    php artiisan migrate --ansi
            MYSQL
            MARIADB
            POSTGRESQL
            SQLITE
            SQL SERVER
    TEST
     PEST

    CONFIRGUURATION
     No configuration  file on the follder
        php artisan  config:publish
        php artisan  config:publish --all
        Give the  lists  of file

    NEW FEATURES
    One Function
    Eager  loading
        return User::with([
            'posts' => fn(sq) => Sq->latest()->limit(3)
        ])->get();
    Encryption
       php artisan model:show User

       php artisan db:table users

    Laravel  Reverb
        parts@porschereading.co.uk













    
