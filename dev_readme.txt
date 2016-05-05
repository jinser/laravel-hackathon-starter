%%%%%Setting up new development environment

1) Rename .env.example to .env
2) Update mysql details where:
    DB_HOST:localhost
    DB_DATABASE:c9
    DB_USERNAME:<user in the bash terminal>
    DB_PASSWORD: <leave this empty>
2) Create databases by running:
    php artisan migrate:install 
    php artisan migrate:refresh (creates all tables)
    php artisan db:seed (adds in dummy data)
3) Check that database c9 has the test user 
4) Run php artisan migrate:install 