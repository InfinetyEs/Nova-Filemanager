<?php

namespace Infinety\Filemanager\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Filesystem\FilesystemAdapter;

class FileRemoved
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
    public function __construct(FilesystemAdapter $storage, string $filePath)
    {
        $this->storage = $storage;
        $this->filePath = $filePath;
    }
}
