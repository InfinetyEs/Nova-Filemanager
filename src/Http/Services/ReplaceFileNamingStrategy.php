<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\UploadedFile;

class ReplaceFileNamingStrategy extends AbstractNamingStrategy
{
    public function name(string $currentFolder, UploadedFile $file): string
    {
        $filename = $file->getClientOriginalName();

        if ($this->storage->exists($currentFolder . '/' . $filename)) {
            $this->storage->delete($currentFolder . '/' . $filename);
        }

        return $filename;
    }
}
