
Install
---
```
cp .env.local .env
cp app/.env.local .env

docker-compose build --no-cache
docker network create testing_network
docker-compose up -d
docker exec -it testing.local.php-fpm composer install
docker exec -it testing.local.php-fpm php bin/console doctrine:migrations:migrate
docker exec -it testing.local.php-fpm php bin/console doctrine:fixtures:load
```

Url
---
http://localhost:8181


Routes
---
Add new player
```
POST /player
Content-Type: application/json

{"name":"New Player","country":"Bulgaria","birth_date":"1990-04-22","position": "midfielder"}
```
---
Edit player
```
PUT /player/{id}
Content-Type: application/json

{"id":123,"name":"New Player","country":"Bulgaria","birth_date":"1990-04-22","position": "midfielder"}
```
---
Get one by id
```
GET /player/{id}
Content-Type: application/json
```
---
Get players list
```
GET /player/{id}?offset=0&limit=50&position=keeper&country=Bulgaria
Content-Type: application/json
```
---
Delete one
```
DELETE /player/{id}
Content-Type: application/json
```

Tests
---
```
docker exec -it testing.local.php-fpm php bin/phpunit
```