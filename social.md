# SETTINGS
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
    php artisan make:model Post -m
    php artisan make:model PostAttachment -m
    php artisan make:model PostReaction -m
    php artisan make:model Comment -m
    php artisan make:model Group -m
    php artisan make:model GroupUser -m
    php artisan make:model Follower -m
    php artisan make:model Notification -m
