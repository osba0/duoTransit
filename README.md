## DuoTransit
DuoTransit est une application WEB permettant de gérer et de superviser les commandes entre le transitaire et le client. 

**Installation**

```
git clone https://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@github.com:osba0/duoTransit.git

cd duotransit/

git fetch && git checkout master

composer install

cp .env.example .env

php artisan key:generate
```

**Configuration .env**

**Migration Database**
```
php artisan migrate
php artisan db:seed
```
