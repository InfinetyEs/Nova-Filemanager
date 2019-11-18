# Using the field

To add the `Filemanager Field` to a resource, we can simply add it to the resource's `fields` method.

```php
use Infinety\Filemanager\FilemanagerField;

/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        FilemanagerField::make('Image'),
    ];
}
```

::: tip Store
Filemanager field will store a `string` in your DB column
:::

## Search

Introducing Nova 2 is possible to custom fields to serve images for global search results. 
We added this feature in Nova Filemanager v2.2 :smile:

## Field Options

This field has some options to fit your needs.

### displayAsImage

Use this method if you want to have a similiar `Image field`. This will show an image in index, view, and edit pages.

```php
FilemanagerField::make('Image')->displayAsImage(),
```

You can use `rounded` and `squared` methods to customize the preview and global search as in [Image field](https://nova.laravel.com/docs/2.0/resources/fields.html#image-field). Default to `rounded`.

### folder

Use this method to set an initial folder for your field. This method accept `Callable` or `String`.

```php
FilemanagerField::make('Image')->folder('users'),

//or

FilemanagerField::make('Image')->folder(function() {
	return auth()->user()->id;
}),
```


### filterBy

If you want to filter all your files you can use this method. The given data should be a lowercase key of your filters in `config/filemanager.php `. 

```php
FilemanagerField::make('Image')->filterBy('images'),
```

### privateFiles

By default, the visibility of all uploaded files is `public`. If you want to upload `private` files use this method.

```php
FilemanagerField::make('Image')->privateFiles(),
```

### validateUpload

You can add rules to validate the upload. Rules should be type as `strings`. Validation objects and closures are not working.

```php
//Working
FilemanagerField::make('Image')->validateUpload('image', 'max:5000'),


//Not working
FilemanagerField::make('Image')->validateUpload('image', new ValidSize),

FilemanagerField::make('Image')->validateUpload('image', function($attribute, $value, $fail) {
    if (filesize($value) > 5000) {
        return $fail('The '.$attribute.' field must under 5Mb.');
    }
}),

```

::: warning Remember
Only string typed rules will be validated in the upload
:::


### hideUploadButton

This option will hide the upload button in the field.

### hideCreateFolderButton

This option will hide the create folder button in the field.

### hideRenameFolderButton

This option will hide the rename button in the folders.

### hideDeleteFolderButton

This option will hide the delete button in the folders.

### hideRenameFileButton

This option will hide the rename button in the files.

### hideDeleteFileButton

This option will hide the delete button in the files.

### noDragAndDropUpload

This option will be remove the drag and drop upload in the field.

::: tip Info
Methods: `hideUploadButton`, `hideCreateFolderButton`, `hideRenameFolderButton`, `hideDeleteFolderButton`, `hideRenameFileButton`, `hideDeleteFileButton`, `noDragAndDropUpload` will overwrite the config options.
:::


## Chain methods

Of course you can chain methods of the field as your own fits.

```php
FilemanagerField::make('Image')->displayAsImage()->filterBy('images'),

FilemanagerField::make('Image')
	->privateFiles()
	->folder(function() {
		return auth()->user()->id;
	}),
```
