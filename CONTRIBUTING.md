# Contributing

 * Coding standard for the project is [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * Any contribution must provide tests for additional introduced conditions

## Installation

To install the project and run the tests, you need to clone it first:

```sh
$ git clone git@github.com:erickskrauch/yii2-rbac-migration.git
```

You will then need to run a composer installation:

```sh
$ cd yii2-rbac-migration
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install
```

## Testing

The PHPUnit version to be used is the one installed as a dev- dependency via composer:

```sh
$ ./vendor/bin/phpunit
```

or

```sh
$ composer test
```
