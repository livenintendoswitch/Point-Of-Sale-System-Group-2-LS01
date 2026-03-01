composer install

cp -n .env.example .env

php artisan key:generate

sed -i 's/^#* *DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i 's/^#* *DB_HOST=.*/DB_HOST=mysql/' .env
sed -i 's/^#* *DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/^#* *DB_DATABASE=.*/DB_DATABASE=POSdatabase/' .env
sed -i 's/^#* *DB_USERNAME=.*/DB_USERNAME=admin/' .env
sed -i 's/^#* *DB_PASSWORD=.*/DB_PASSWORD=123456789/' .env

php artisan migrate

php artisan serve --host=0.0.0.0 --port=8000