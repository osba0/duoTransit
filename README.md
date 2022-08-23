## About DuoTransit 

**Installation**

```
git clone git@github.com:YouWeApS/order_auto_print2.git

cd duotransit/

git fetch && git checkout master

composer install

cp .env.example .env

php artisan key:generate
```

**Configuration .env**
```
php artisan migrate
```
