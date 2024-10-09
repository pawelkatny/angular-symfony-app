# Symfony API
Simple Symfony API with JWT authentication.

Notice: Angular client app is unfinished.

## General Information
API authentication and JWT handling was based on LexikJWTAuthenticationBundle. 

The bundle provides few extra features out of the box:
- token generator and extractor
- json login path
- built-in token validator
- setting cookie

## Technologies used
- Symfony ver=7.1
- Sqlite
- Docker

## Local deployment
Prerequisites: docker installed

Clone this repository
```
git clone https://github.com/pawelkatny/angular-symfony-app.git

cd angular-symfony-app
```

Run docker compose
```
docker compose up --build -d
```

Enter symfony api container
```
docker exec -it $(docker ps -q -f "name=api") /bin/ash
```

Create database and run migrations
```
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

# in case of any issues while writing/reading to database, change ownership of data.db file
# alpine default user
# chown www-data:www-data var/data.db
#
# od dockers default
# chown 1000:1000 var/data.db

```

Create JWT keypair and passphrase
```
php bin/console lexik:jwt:generate-keypair
```
Default token ttl is 3600. This can be changed in 
```
# config/packages/lexik_jwt_authentication.yaml
lexik_jwt_authentication:
    token_ttl: 3600
```

Generate user with 'ROLE_ADMIN' using command `app:create-admin-user`
```
php bin/console  app:create-admin-user admin@test.pl password
```

Server runs on `localhost:8080`. Every request should be send with `/api` prefix.





