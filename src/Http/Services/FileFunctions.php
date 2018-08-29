<?php

namespace Infinety\Filemanager\Http\Services;

trait FileFunctions
{
    /**
     * @param $path
     *
     * @return string
     */
    public function checkPerms($path)
    {
        clearstatcache(null, $path);

        return decoct(fileperms($path) & 0777);
    }

    /**
     * @param $size
     * @param int $level
     * @param int $precision
     * @param int $base
     *
     * @return string
     */
    public function formatBytes($size, $level = 0, $precision = 2, $base = 1024)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $times = floor(log($size, $base));

        return sprintf('%.'.$precision.'f', $size / pow($base, ($times + $level))).' '.$unit[$times + $level];
    }

    /**
     * Clean Slashes.
     *
     * @param $str
     *
     * @return string
     */
    public function cleanSlashes($str)
    {
        return preg_replace('#/+#', '/', $str);
    }
}
