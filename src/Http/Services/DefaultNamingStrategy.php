<?php

namespace Infinety\Filemanager\Http\Services;

class DefaultNamingStrategy extends AbstractNamingStrategy
{
    public function name($currentFolder, $file) : string
    {
        $filename = $file->getClientOriginalName();

        while ($this->storage->has($currentFolder.'/'.$filename)) {
            $filename = sprintf(
                '%s_%s.%s',
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                str_random(7),
                $file->getClientOriginalExtension()
            );
        }

        return $filename;
    }
}
