# Laravel Insert On Conflict

This package provides macros to run INSERT ... ON CONFLICT DO NOTHING or DO UPDATE SET queries on models with Laravel's ORM Eloquent using PostgreSQL.

## Installation

Install this package with composer.

```sh
composer require rits-tecnologia/eloquent-insert-on-conflict
```

If you don't use Package Auto-Discovery yet add the service provider to your Package Service Providers in `config/app.php`.

```php
InsertOnConflict\InsertOnConflictServiceProvider::class,
```

## Usage

### Models

Call `insertOnConflict` from a model with the array of data to insert in its table.

```php
$data = [
    ['id' => 1, 'name' => 'name1', 'email' => 'user1@email.com'],
    ['id' => 2, 'name' => 'name2', 'email' => 'user2@email.com'],
];

User::insertOnConflict($data);

```

#### Customizing the ON CONFLICT DO UPDATE clause

##### Update only certain columns

If you want to update only certain columns, pass them as the 2nd argument.

```php
User::insertOnConflict([
    'id'    => 1,
    'name'  => 'new name',
    'email' => 'foo@gmail.com',
], ['name'], 'do update set');
// The name will be updated but not the email.
```

##### Update with custom values

You can customize the value with which the columns will be updated when a row already exists by passing an associative array.

In the following example, if a user with id = 1 doesn't exist, it will be created with name = 'created user'. If it already exists, it will be updated with name = 'updated user'.

```php
User::insertOnConflict([
    'id'    => 1,
    'name'  => 'created user',
], ['name' => 'updated user'], 'do update set');
```

The generated SQL is:

```sql
INSERT INTO `users` (`id`, `name`) VALUES (1, "created user") ON CONFLICT DO UPDATE SET `name` = "updated user"
```

You may combine key/value pairs and column names in the 2nd argument to specify the columns to update with a custom literal or expression or with the default `VALUES(column)`. For example:

```php
User::insertOnConflict([
    'id'       => 1,
    'name'     => 'created user',
    'email'    => 'new@gmail.com',
    'password' => 'secret',
], ['name' => 'updated user', 'email'], 'do update set');
```

will generate

```sql
INSERT INTO `users` (`id`, `name`, `email`, `password`)
VALUES (1, "created user", "new@gmail.com", "secret")
ON CONFLICT DO UPDATE SET `name` = "updated user", `email` = VALUES(`email`)
```
