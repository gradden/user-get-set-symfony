# User getter and setter in Symfony

A simple Backend for getting and setting a user, written in PHP via Symfony framework.

## Installation

1. Clone the repository
```
   git clone https://github.com/gradden/user-get-set-symfony
```
2. Make a .env file, set up docker and enter to the container's terminal

```
   cd user-get-set-symfony
   cp .env.example .env
```
3. Configure .env
```
APP_ENV=dev  
APP_SECRET=YourSecretKey
APP_DEBUG_EXCEPTION=true  #Set to true if you want to see the trace of exception in API responses.

DB_DATABASE=symfony  
DB_USER=test  
DB_PASSWORD=1234  #Fill this
DB_ROOT_PASSWORD=1234  #Fill this
```
If you have Symfony or PHP installed on your computer, you can use the implemented secret generator with the following command. After running the command, you'll get a 32 character long secret key, which you need to insert into your .env file [APP_SECRET=].
```
php bin/console app:generate-secret
```
4. Set up docker and enter to the container's terminal
```
docker compose up -d
docker exec -it -u application symfony bash
```
5. Composer install & run migrations
```
composer install
php bin/console doctrine:migrations:migrate
```

---
## API
If the installation completed, you can reach the Backend in local environment on the following URL: http://localhost:8080/ <br>
API List:
- <b>POST</b> /api/users
- <b>GET</b> /api/users
- <b>GET</b> /api/users/{id}

Defaultly the response will be in JSON, but you can set to YAML by changing the *Accept* field in header. (See the example below)

---
### Example

Endpoint: ***POST** /api/users*

- BODY:
```
{
    "firstName" : "Foo",
    "lastName" : "Bar",
    "email" : "foobar@mail.com",
    "password" : "supersecret1234"
}
```
- HEADER:
```
Accept: application/json
```
Or
```
Accept: application/x-yaml
```

- RESPONSE:
```
{
    "id": 50,
    "firstName": "Foo",
    "lastName": "Bar",
    "email": "foobar@mail.com"
}
```
