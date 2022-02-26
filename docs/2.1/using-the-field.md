# Using the field

To add the `Filemanager Field` to a resource, we can simply add it to the resource's `fields` method.

```php
use Grayloon\Filemanager\FilemanagerField;

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

## Field Options

This field has some options to fit your needs.

### displayAsImage

Use this method if you want to have a similiar `Image field`. This will show an image in index, view, and edit pages.

```php
FilemanagerField::make('Image')->displayAsImage(),
```

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
