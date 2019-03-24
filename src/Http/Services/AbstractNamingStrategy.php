<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\Filesystem;

abstract class AbstractNamingStrategy
{
    /**
     * @var Filesystem
     */
    protected $storage;

    public function __construct(Filesystem $storage)
    {
        $this->storage = $storage;
    }

    abstract public function name(string $currentFolder, UploadedFile $file) : string;
}
