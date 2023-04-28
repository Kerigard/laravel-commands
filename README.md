# Laravel Utils

<p align="center">
  <a href="https://github.com/Kerigard/laravel-utils/actions"><img src="https://github.com/Kerigard/laravel-utils/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/Kerigard/laravel-utils"><img src="https://img.shields.io/packagist/dt/Kerigard/laravel-utils" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/Kerigard/laravel-utils"><img src="https://img.shields.io/packagist/v/Kerigard/laravel-utils" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/Kerigard/laravel-utils"><img src="https://img.shields.io/packagist/l/Kerigard/laravel-utils" alt="License"></a>
</p>

Utils for Laravel 10 and up.

## Installation

Install package via composer:

``` bash
composer require kerigard/laravel-utils
```

Publish the configuration file using the `vendor:publish` artisan command to configure or disable unnecessary commands:

```bash
php artisan vendor:publish --provider="Kerigard\LaravelUtils\UtilsServiceProvider" --tag=utils-config
```

Publish the stubs files using the `vendor:publish` artisan command to change the structure of generated classes:

```bash
php artisan vendor:publish --provider="Kerigard\LaravelUtils\UtilsServiceProvider" --tag=utils-stubs
```

## Usage

### Pint

Running Laravel Pint via artisan command.

```
pint [options] [--] [<paths>...]
```

| Argument | Description                               |
|----------|-------------------------------------------|
| paths    | Run Pint on specific files or directories |

| Option            | Shortcut | Description                                                       |
|-------------------|----------|-------------------------------------------------------------------|
| --verbose         | -v       | Show details of changes                                           |
| --test            | -t       | Inspect code for style errors without actually changing the files |
| --dirty           | -d       | Modify the files that have uncommitted changes according to Git   |
| --preset[=PRESET] | -p       | Use preset with rule set to fix code                              |
| --config[=CONFIG] | -c       | Use pint.json config from a specific directory                    |

#### Examples

Run Laravel Pint.

```bash
php artisan pint
```

Run with arguments:

```bash
php artisan pint app/Models routes/api.php -t --preset psr12 --config vendor/my-company/coding-style/pint.json
```

### Make Enum

Create a new enum class.

```
make:enum [options] [--] <name>
```

| Argument | Description          |
|----------|----------------------|
| name     | The name of the enum |

| Option  | Shortcut | Description                                      |
|---------|----------|--------------------------------------------------|
| --force | -f       | Create the class even if the enum already exists |
| --help  | -h       | Display help for the given command               |

#### Examples

Create a enum class:

```bash
php artisan make:enum Status
```

> Creates a file `app/Enums/Status.php`.

### Make Trait

Create a new trait class.

```
make:trait [options] [--] <name>
```

| Argument | Description           |
|----------|-----------------------|
| name     | The name of the trait |

| Option  | Shortcut | Description                                       |
|---------|----------|---------------------------------------------------|
| --force | -f       | Create the class even if the trait already exists |
| --help  | -h       | Display help for the given command                |

#### Examples

Create a trait class:

```bash
php artisan make:trait HasRoles
```

> Creates a file `app/Traits/HasRoles.php`.

### Make Contract

Create a new contract interface.

```
make:contract [options] [--] <name>
```

| Argument | Description              |
|----------|--------------------------|
| name     | The name of the contract |

| Option   | Shortcut | Description                                              |
|----------|----------|----------------------------------------------------------|
| --action | -a       | Create a contract for an action                          |
| --force  | -f       | Create the interface even if the contract already exists |
| --help   | -h       | Display help for the given command                       |

#### Examples

Create a contract interface:

```bash
php artisan make:contract CreatesUser
```

> Creates a file `app/Contracts/CreatesUser.php`.

Create a contract for action:

```bash
php artisan make:contract CreatesUser --action
```

> Creates a file `app/Contracts/CreatesUser.php`.

### Make Action

Create a new action class.

```
make:action [options] [--] <name>
```

| Argument | Description            |
|----------|------------------------|
| name     | The name of the action |

| Option                | Shortcut | Description                                        |
|-----------------------|----------|----------------------------------------------------|
| --contract[=CONTRACT] | -c       | Create a new contract for the action               |
| --force               | -f       | Create the class even if the action already exists |
| --help                | -h       | Display help for the given command                 |

#### Examples

Create a action class:

```bash
php artisan make:action CreateUser
```

> Creates a file `app/Actions/CreateUser.php`.

Create action and contract:

```bash
php artisan make:action CreateUser --contract CreatesUser
```

> Creates `app/Actions/CreateUser.php` and `app/Contracts/CreatesUser.php` files.

Create action and contract with the same name:

```bash
php artisan make:action CreateUser --contract
```

> Creates `app/Actions/CreateUser.php` and `app/Contracts/CreateUser.php` files.

### Make Service

Create a new service class.

```
make:service [options] [--] <name>
```

| Argument | Description             |
|----------|-------------------------|
| name     | The name of the service |

| Option                | Shortcut | Description                                         |
|-----------------------|----------|-----------------------------------------------------|
| --contract[=CONTRACT] | -c       | Create a new contract for the service               |
| --force               | -f       | Create the class even if the service already exists |
| --help                | -h       | Display help for the given command                  |

#### Examples

Create a service class:

```bash
php artisan make:service UserService
```

> Creates a file `app/Services/UserService.php`.

Create service and contract:

```bash
php artisan make:service UserService --contract User
```

> Creates `app/Services/UserService.php` and `app/Contracts/User.php` files.

Create service and contract with the same name:

```bash
php artisan make:service UserService --contract
```

> Creates `app/Services/UserService.php` and `app/Contracts/UserService.php` files.

## Changelog

Please see the [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

MIT. Please see the [LICENSE FILE](LICENSE.md) for more information.
