# Jobs

You can create your own jobs to run after a file is uploaded.

First edit `jobs` key on `config` file. You can create as many jobs as you have filters. To add new job use one of the keys used in filters in lowercase.

For example:

```php
'jobs'      => [
    'images' => App\Jobs\ResizeImages::class,
    'compressed' => App\Jobs\DecompressFile::class,
],
```

You should add two paramaters to your `construct` job function.

* `storage`: `FilesystemAdapter $storage` used to upload the file.
* `filePath`: String with the full path of the upload file.

For example:


```php
	use Illuminate\Filesystem\FilesystemAdapter;

	/**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $filePath;

	 /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FilesystemAdapter $storage, String $filePath)
    {
        $this->storage = $storage;
        $this->filePath = $filePath;
    }
```
