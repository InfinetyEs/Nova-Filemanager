# Using the tool

To use the tool just enter in your nova installation and click on `Filemanager` link of the sidebar.

The Filemanager will shown.

![Filemanager](/home.png)


## Storage disk

Filemanager uses Storage to work with files. Set your default disk of the filemanager in the config file. 

::: warning
Some functions like `getimagesize` or `size` are not available in some drivers.
:::


## Order

You can order your files in Filemanager. Just modify the config option.

You can use `mime`, `name` or `size`.

If you want to modify the `order direction` you can modify the option too. 

Available options are `asc` or `desc`.


## Cache

You can use cache in Filemanager. This could be very useful when s3 is being used or when needs to read a lot of files.

Filemanger will cache each file using the `name` and the `time` of the file.

By default cache is set to false. You can specify the cache time you need. 

Available options are `false` or `numbers`.

::: warning
Cache is **set by file**, not by folder
:::



## Uploads

Just `Drag&Drop` into the Filemanager to start uploading. Or click the `Upload` button.


### Naming strategy

You can resolve the upload file name creating your own class. [Check the docs](/2.1/customization.html#naming-strategy)


## Folders

You can create folders clicking in the `Create folder` button.

## View / List

You can change the defauult view of the `Filemanager`. Once click it's saved in your browser localStorage for future. Default view is Grid. 

## Filters

You can use Filters to filter files by extension.

Filters are defined on `filters` section in the [config File](/2.1/installation.html#configuration-file).

Default filters are:

```php
 'filters'   => [

    'Images'     => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'tiff'],

    'Documents'  => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pps', 'pptx', 'odt', 'rtf', 'md', 'txt'],

    'Videos'     => ['mp4', 'avi', 'mov', 'mkv', 'wmv', 'flv', '3gp', 'h264'],

    'Audios'     => ['mp3', 'ogg', 'wav', 'wma', 'midi'],

    'Compressed' => ['zip', 'rar', 'tar', 'gz', '7z', 'pkg'],

],
```

But You can `create|modify|delete` as you want.

For example you can create a new filter called `Development`:

```php
	'Development' => ['php', 'js', 'vue', 'make'],
```

::: tip Default filter
You can also filter by default all files. Use the `filter` option to choose a filter family.
:::


For example:

```php
'filter'    => 'development',
```

Use a `lowercase` of your key family.


## Search

You can search in your current files using the search box.


## File details

You can view file details clicking on a file. This will throw a popup with the file details.

![Preview](/preview.png)

Our Filemanager is able to show the preview of a lot of types so far:


| Type   | Preview                     | Description                                     | 
|--------|-----------------------------|-------------------------------------------------| 
| Images | Image preview               | All images with a image mime type and SVG files | 
| Audios | Audio player                | All files with audio mime type                  | 
| Videos | Video player                | All files with video mime type                  | 
| Texts  | Codemirror viewer           | All files with text, plain or sql mime type     | 
| Zips   | Tree view listing all files | All files with zip mime type                    | 
| PDFs   | Google previews             | All files pdf mime type                         | 


## Rename

In file preview you can rename the file

## Delete

In file preview you're able to delete the file

