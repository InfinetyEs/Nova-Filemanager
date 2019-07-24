<?php

namespace Infinety\Filemanager\Events;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Queue\SerializesModels;

class FileUploaded
{
    use SerializesModels;

    /**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $filePath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FilesystemAdapter $storage, String $filePath)
    {
        $this->storage = $storage;
        $this->filePath = $filePath;
    }
}
