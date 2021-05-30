<?php


namespace Infinety\Filemanager\Traits;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

trait StorageHelpers
{
    public function url(FilesystemAdapter $fs, $path, $expiration = "+ 1 hour", $options=[]){
        $adapter = $fs->getDriver()->getAdapter();
        if ($adapter instanceof AwsS3Adapter){
            return $fs->getAwsTemporaryUrl($adapter, $path, $expiration, $options);
        }
        return $fs->url($path);
    }
}
