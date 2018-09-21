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


### Installation

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

### Tool Usage

Click on the "FileManager" menu item in your Nova app to see the Filemanager Tool


### Field Usage

```php
use Infinety\Filemanager\FilemanagerField;

FilemanagerField::make('field');


// You can set also if you want preview Images in Index and Detail views

FilemanagerField::make('field')->displayAsImage();

```

### Localization

Set your translations in the corresponding xx.json file located in /resources/lang/vendor/nova

```json
...

    "Filemanager": "Gestor de archivos",
    "Create folder": "Crear carpeta",
    "Write a folder name": "Escribe el nombre de la carpeta",
    "Create": "Crear",
    "Creating": "Creando",
    "Cancel": "Cancelar",
    "The folder name is required": "El nombre de la carpeta es requerido",
    "Folder created successfully": "Carpeta creada correctamente",
    "Error creating the folder": "Error al crear la carpeta",
    "Preview of": "Vista previa de",
    "Name": "Nombre",
    "Mime Type": "Tipo de archivo",
    "Last Modification": "Última modificación",
    "Size": "Tamaño",
    "Dimensions": "Dimensiones",
    "Url": "Enlace",
    "Copy": "Copiar",
    "Select file": "Seleccionar archivo",
    "Remove File": "Eliminar archivo",
    "Are you sure?": "¿Estás seguro?",
    "Removing...": "Eliminando...",
    "Text copied to clipboard": "Texto copiado al portapapeles",
    "File removed successfully": "Archivo eliminado correctamente",
    "Error removing the file. Please check permissions": "Error al eliminar el archivo. Por favor, comprueba los permisos",
    "Home": "Inicio",
    "You don\\'t have permissions to view this folder": "No tienes permisos para ver esta carpeta",
    "No files or folders in current directory": "Esta carpeta esta vacía",
    "Remove directory": "Eliminar carpeta",
    "Folder removed successfully": "Carpeta eliminada correctamente",
    "Error removing the folder. Please check permissions": "Error al eliminar la carpeta. Por favor, comprueba los permisos",
    "Upload": "Subir",
    "Error on upload": "Error al subir",
    "Success": "Éxito",
    "Error uploading the file. Check your MaxFilesize or permissions": "Error al subir el archivo. Comprueba la directiva MaxFilesize o los permisos",
    "Select a file": "Selecciona un archivo",
    "Open FileManager": "Abrir Gestor de archivos",
    "This image": "Esta imagen",
    "could not be found.": "no se encuentra."
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
