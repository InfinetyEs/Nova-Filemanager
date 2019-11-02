<?php

namespace Infinety\Filemanager\Events;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Queue\SerializesModels;

class FolderUploaded
{
    use SerializesModels;

    /**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $folderPath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FilesystemAdapter $storage, string $folderPath)
    {
        $this->storage = $storage;
        $this->folderPath = $folderPath;
    }
}
