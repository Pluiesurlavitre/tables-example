# Simple table example app

This is a simple example of a laravel + svelte app.  


## Installation

Clone the project

### Laravel

```
cd api
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
```

### Svelte

```
cd front
npm install
npm run dev
```

Then, visit `localhost:5000`.

## Features

* Pagination
* Click on table headers to sort

