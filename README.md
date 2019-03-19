# Filemanager tool for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)
[![CircleCI branch](https://circleci.com/gh/InfinetyEs/Nova-Filemanager.svg?style=shield&circle-token=85960312b6610a79d7d720227c75e440f419323d)](https://circleci.com/gh/InfinetyES/Nova-Filemanager)
[![StyleCI](https://github.styleci.io/repos/146585053/shield?branch=master)](https://github.styleci.io/repos/146585053)
[![Total Downloads](https://img.shields.io/packagist/dt/infinety-es/nova-filemanager.svg?style=flat-square)](https://packagist.org/packages/infinety-es/nova-filemanager)

A Filemanager Tool and Field for Laravel Nova

##### Filemanager Tool preview

![FileManager Tool](https://user-images.githubusercontent.com/42798230/44862985-d3d57b80-ac73-11e8-9169-2e76a3584ea4.gif)

##### Filemanager Field preview

![FileManager Field](https://user-images.githubusercontent.com/42798230/44864362-5f9cd700-ac77-11e8-9e0f-330d18a81598.gif)


### Installation

You can install the package in any Laravel app that uses [Nova](https://nova.laravel.com) via Composer:

```bash
composer require infinety-es/nova-filemanager
```

Next, publish config and translation files: 

```bash
php artisan vendor:publish --tag=filemanager-config
php artisan vendor:publish --tag=filemanager-lang
```

Also, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

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

### Upgrade from v1

Please publish thc config file after update.

`Folder` option now limits the home. So now, when you set a initial folder, this will be the home. 

### Tool Usage

Click on the "FileManager" menu item in your Nova app to see the Filemanager Tool


### Field Usage

```php
use Infinety\Filemanager\FilemanagerField;

FilemanagerField::make('field');


// You can also show preview images in Index, Detail and Edit views

FilemanagerField::make('field')->displayAsImage();


// You can set an initial folder

FilemanagerField::make('field')->folder('avatars');


// You can set the visibiility of your files private, using this method

FilemanagerField::make('field')->privateFiles();

// You can also filter all the files using next method. 
// The given data should be a lowercase key of your filters in config/filemanager.php 

FilemanagerField::make('field')->filterBy('images')

```


### Config file

Nova Filemanager works with [Laravel Flysystem](https://github.com/GrahamCampbell/Laravel-Flysystem). The default disk is `public`. You can customize the Flysystem disk and other options using config file:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filemanager Disk
    |--------------------------------------------------------------------------
    | This is the storage disk FileManager will use to put file uploads, you can use
    | any of the disks defined in your config/filesystems.php file. Default to public.
     */

    'disk'      => env('FILEMANAGER_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filemanager default order
    |--------------------------------------------------------------------------
    | This will set the default order of the files and folders.
    | You can use mime, name or size. Default to mime
     */

    'order'     => env('FILEMANAGER_ORDER', 'mime'),

    /*
    |--------------------------------------------------------------------------
    | Filemanager default order direction
    |--------------------------------------------------------------------------
    | This will set the default order direction of the files and folders.
    | You can use mime, name or size. Default to asc
     */

    'direction' => env('FILEMANAGER_DIRECTION', 'asc'),

    /*
    |--------------------------------------------------------------------------
    | Filemanager cache
    |--------------------------------------------------------------------------
    | This will set the cache of filemenager. Filemanager creates a  md5 using file
    | info. This is useful when s3 is being used or when needs to read a lot of files.
    | Cache is set by file, not by folder. Default to false.
     */

    'cache'     => env('FILEMANAGER_CACHE', false),

    /*
    |--------------------------------------------------------------------------
    | Filemanager  filters
    |--------------------------------------------------------------------------
    | This option let you to filter your files by extensions. You can create|modify|delete as you want.
     */

    'filters'   => [

        'Images'     => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'tiff'],

        'Documents'  => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pps', 'pptx', 'odt', 'rtf', 'md', 'txt'],

        'Videos'     => ['mp4', 'avi', 'mov', 'mkv', 'wmv', 'flv', '3gp', 'h264'],

        'Audios'     => ['mp3', 'ogg', 'wav', 'wma', 'midi'],

        'Compressed' => ['zip', 'rar', 'tar', 'gz', '7z', 'pkg'],

    ],

    /*
    |--------------------------------------------------------------------------
    | Filemanager  default filter
    |--------------------------------------------------------------------------
    | This will set the default filter for all your Filemanager. You shoulw use one
    | of the keys used in filters in lowercase. If you have a key called Documents,
    | use 'documents' as your default filter. Default to false
     */

    'filter'    => false,
];

```

### Testing

``` bash
composer test
yarn lint
yarn check-format
```

### ToDo

* ~~AWS S3 support~~
* ~~Different upload method~~
* Grid / List views

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email apps@infinety.es instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Infinety - Calle Comedias, 8 Floor 3, Suite 5 46003 Valencia (Spain).

## Credits

- [Eric Lagarda](https://github.com/Krato)
- [Spatie Nova Tool Skeleton](https://github.com/spatie/skeleton-nova-tool)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
