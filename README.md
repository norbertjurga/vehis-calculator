## Vehis - Recruitment Task

Local enviroment installation
```
cp .env.example .env #replace with your enviroment variables

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

./vendor/bin/sail up 
./vendor/bin/sail artisan migrate

./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Build for production
```
./vendor/bin/sail npm run build
```



Run tests
```
./vendor/bin/sail artisan test
```
