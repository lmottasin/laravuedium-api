### Laravuedium-API-Docker [Backend of Laravuedium]

![laravuedium banner](public/images/laravudium.jpg)

> Laravuedium is a blog app like medium with Vue and Laravel. This is backend.

### Setup Instructions

> Clone this repository to your device and run this commands:

> Copy `.env.example` file to `.env`

```sh
cp .env.example .env
```

> Configure `.env` file with your own credentials

### With Docker
```sh
# start the docker containers
docker compose up

# to access the php container, use this command
docker exec -it php /bin/sh

# install composer from the php container
composer install

# give permission to the storage folder of the application
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# generate the application key using the following command
php artisan key:generate

# storage symlink
docker exec -it php /bin/sh -c "cd public && ln -s ../storage/app/public storage"

# migrate the tables to the database and seed fake data for testing
php artisan migrate --seed

# check running docker containers status
docker compose ps

# shut down all running docker containers.
docker compose down
```

### Without Docker
```sh
composer install

npm install

php artisan key:generate

php artisan migrate

npm run dev

php artisan storage:link
```