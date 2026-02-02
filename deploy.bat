@echo off
echo Deploying to Production...

echo Installing dependencies...
call composer install --optimize-autoloader --no-dev

echo Building assets...
call npm run build

echo Clearing caches...
call php artisan optimize:clear

echo Caching configuration and routes...
call php artisan config:cache
call php artisan event:cache
call php artisan route:cache
call php artisan view:cache

echo Running migrations...
call php artisan migrate --force

echo Deployment complete!
pause
