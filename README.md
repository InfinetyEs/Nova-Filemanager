# Filemanager tool for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)
![CircleCI branch](https://img.shields.io/circleci/project/github/infinety-es/nova-filemanager/master.svg?style=flat-square)
[![Build Status](https://img.shields.io/travis/infinety-es/nova-filemanager/master.svg?style=flat-square)](https://travis-ci.org/infinety-es/nova-filemanager)
[![Quality Score](https://img.shields.io/scrutinizer/g/infinety-es/nova-filemanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/infinety-es/nova-filemanager)
[![Total Downloads](https://img.shields.io/packagist/dt/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)


A Filemanager tool and Field for Laravel Nova

[Image ToDo]

[Field ToDo]

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require infinety-es/nova-filemanager
```

Next up, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \Infinety\Filemanager\FilemanagerTool(),
    ];
}
```

## Usage

Click on the "FileManager" menu item in your Nova app to see the Filemanager Tool


## Field Use

```php
use Infinety\Filemanager\FilemanagerField;

FilemanagerField::make('field');

```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Infinety - Calle Comedias, 8 Floor 3, Suite 5 46003 Valencia (Spain).

## Credits

- [Eric Lagarda](https://github.com/Krato)
- [Spatie Nova Tool Skeleton](https://github.com/spatie/skeleton-nova-tool)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
