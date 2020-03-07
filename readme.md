# Simple API REST SOLID + Tests

Using Lumen, Facades, Eloquent, SOLID, PHPUnit, Mockery

## Version

1.0.0

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

What things you need to install the software

```
PHP 7.0+, MySQL, Apache or Nginx and Composer
```

### Installing

After cloning the project, in his folder:

Run composer

```
composer install
```

Copy your .env file

```
cp .env.dist .env
```

Configure your database in .env file

```
vi .env
```

Run artisan migrate to create tables

```
php artisan migrate
```

Host the project

```
php -S localhost:8000 -t public
```

Now access your localhost to test

```
http://localhost:8000/client
```

## Testing the API endpoints

Use list bellow to test each one of the API endpoints

### Create Client

POST /client

```
curl -i -X POST -H "Content-Type:application/json" http://localhost:8000/api/v1/candidates -d '{"name":"Leo", "cpf":"00217064175"}'
```

### Edit Client

PUT /client/1

```
curl -H "Content-Type:application/json" -X PUT http://localhost:8000/api/v1/candidates/1 -d '{"name":"Leonardo Da Vinci", "cpf":"00217064175"}'
```

### List All Clients

GET /client

```
curl http://localhost:8000/client
```

### View Client Details

GET /client/1

```
curl http://localhost:8000/client/1
```

## Built With

* [Bootstrap](https://getbootstrap.com)
* [Font Awesome](http://fontawesome.io)
* [jQuery](https://jquery.com)
* [Laravel Eloquent](https://laravel.com/docs/5.5/eloquent)
* [Laravel Facades](https://laravel.com/docs/5.5/facades)
* [Lumen](https://lumen.laravel.com)
* [PHPUnit](https://phpunit.de/)
* [Mockery](http://docs.mockery.io/en/latest/)
* [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design))

## Versioning

I use [SemVer](http://semver.org/) for versioning. 

## Authors

* **Leonardo Di Sarli** - *Initial work* - [DiSarli](http://disarli.com.br)

## License

This project is licensed under the GNU GENERAL PUBLIC LICENSE - see the [LICENSE.md](LICENSE.md) file for details
