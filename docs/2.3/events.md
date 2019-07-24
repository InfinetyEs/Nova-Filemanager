# Events

Nova Filemanager includes some events:

## Files events

### FileUploaded

Event fired when a file is uploaded

Has two public properties:

* `storage`: `FilesystemAdapter $storage` used to upload the file.
* `filePath`: String with the full path of the file uploaded.

### FileRemoved

Event fired when a file is removed

Has two public properties:

* `storage`: `FilesystemAdapter $storage` used to remove the file.
* `filePath`: String with the full path of the removed file.

## Folder events

### FolderUploaded

Event fired when a folder is uploaded

Has two public properties:

* `storage`: `FilesystemAdapter $storage` used to upload the folder.
* `folderPath`: String with the full path of the folder uploaded.

### FolderRemoved

Event fired when a folder is uploaded

Has two public properties:

* `storage`: `FilesystemAdapter $storage` used to remove the folder.
* `folderPath`: String with the full path of the removed folder.

## How to 

You can create a Listener in your `EventServiceProvider` `$listen` array. For example:

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Infinety\Filemanager\Events\FileUploaded' => [
        'App\Listeners\LogFileUploaded',
    ],
];

```

If you're using Laravel > 5.8 you can use `Event Discovery`. Check [Laravel Docs](https://laravel.com/docs/5.8/events#event-discovery)
