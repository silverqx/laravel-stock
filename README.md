<p align="center">
  <a href="https://www.gatsbyjs.org">
    <img alt="Laravel Stock" src="https://raw.githubusercontent.com/silverqx/laravel-stock/master/resources/images/logo.png" width="60" />
  </a>
</p>
<h1 align="center">
  Laravel Stock
</h1>

[![Heroku](http://heroku-badge.herokuapp.com/?app=angularjs-crypto&style=flat&svg=1)](https://cs-laravel-stock.herokuapp.com)

This is my Laravel Stock test application.

Application is deployed at https://cs-laravel-stock.herokuapp.com.

## Installation

* `git clone https://github.com/silverqx/laravel-stock`
* `cd laravel-stock`
* rename `env.example` na `.env` and fill out correct data
* `composer install`
* `php artisan key:generate` to generate new Security Key ( optional )
* `npm install`
* `npm run dev` or `npm run watch`

## Notes

### Authentication and Authorization

Application contains three users, these are their login data:

* silver.zachara@gmail.com - pass
* peter@example.com - pass
* andrej@example.com - pass

### Others

Uploaded image can be of any dimensions, it will be always cropped properly and the aspect ratio will be preserved.
