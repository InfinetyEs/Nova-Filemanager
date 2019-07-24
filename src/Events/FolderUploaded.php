<?php

namespace Infinety\Filemanager\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Filesystem\FilesystemAdapter;

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
