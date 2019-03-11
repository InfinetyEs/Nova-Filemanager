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
