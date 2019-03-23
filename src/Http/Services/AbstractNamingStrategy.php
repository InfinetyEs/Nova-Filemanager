<?php

namespace Infinety\Filemanager\Http\Services;

abstract class AbstractNamingStrategy
{
    /**
     * @var mixed
     */
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    abstract public function name($currentFolder, $file) : string;
}
