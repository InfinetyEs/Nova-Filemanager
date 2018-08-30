# Filemanager tool for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)
[![CircleCI branch](https://img.shields.io/circleci/project/github/spatie/nova-tags-field/master.svg?style=flat-square)](https://circleci.com/gh/InfinetyES/Nova-Filemanager)
[![StyleCI](https://github.styleci.io/repos/146585053/shield?branch=master)](https://github.styleci.io/repos/146585053)
[![Total Downloads](https://img.shields.io/packagist/dt/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)

A Filemanager Tool and Field for Laravel Nova

##### Filemanager Tool preview

![FileManager Tool](https://user-images.githubusercontent.com/42798230/44862985-d3d57b80-ac73-11e8-9169-2e76a3584ea4.gif)

##### Filemanager Field preview

![FileManager Field](https://user-images.githubusercontent.com/42798230/44864362-5f9cd700-ac77-11e8-9e0f-330d18a81598.gif)


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

Nova Filemanager works with Laravel Flysystem. Default disk is `public`. You can change the Flysystem disk writing that line in your `env.` file:


```env
FILEMANAGER_DISK=public
```

## Tool Usage

Click on the "FileManager" menu item in your Nova app to see the Filemanager Tool


## Field Usage

```php
use Infinety\Filemanager\FilemanagerField;

FilemanagerField::make('field');


// You can set also if you want preview Images in Index and Detail views

FilemanagerField::make('field')->displayAsImage();

```

### Testing

``` bash
composer test
yarn lint
yarn check-format
```

### ToDo

* Grid / List views
* DIfferent upload method
* FIles actions

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
