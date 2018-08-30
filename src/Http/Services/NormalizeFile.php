<?php

namespace Infinety\Filemanager\Http\Services;

use SplFileInfo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\FilesystemAdapter;

class NormalizeFile
{
    use FileFunctions;

    /**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $file;

    /**
     * @var mixed
     */
    protected $storagePath;

    /**
     * @param string $path
     */
    public function __construct(FilesystemAdapter $storage, String $path, String $storagePath)
    {
        $this->storage = $storage;
        $this->file = new SplFileInfo($path);

        $this->storagePath = $storagePath;
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        $data = collect([
            'name' => $this->file->getFilename(),
            'mime' => $this->storage->getMimetype($this->storagePath),
            'path' => $this->storagePath,
            'size' => ($this->file->getSize() != 0) ? $this->formatBytes($this->file->getSize(), 0) : 0,
            'url'  => $this->cleanSlashes($this->storage->url($this->storagePath)),
            'date' => $this->modificationDate($this->file->getMTime()),
        ]);

        $data = $this->setExtras($data);

        return $data->toArray();
    }

    /**
     * @param Collection $data
     * @return mixed
     */
    private function setExtras(Collection $data)
    {
        $mime = $this->storage->getMimetype($this->storagePath);

        // Image
        if (str_contains($mime, 'image')) {
            $data->put('type', 'image');
            $data->put('dimensions', $this->getDimensions($this->storage->getMimetype($this->storagePath)));
        }

        // Video
        if (str_contains($mime, 'audio')) {
            $data->put('type', 'audio');
            $src = str_replace(env('APP_URL'), '', $this->storage->url($this->storagePath));
            $data->put('src', $src);
        }

        // Video
        if (str_contains($mime, 'video')) {
            $data->put('type', 'video');
        }

        $data->put('image', $this->getImage($mime));

        return $data;
    }

    /**
     * Returns the image or the svg icon preview.
     *
     * @return mixed
     */
    private function getImage($mime)
    {
        if (str_contains($mime, 'image')) {
            return $this->storage->url($this->storagePath);
        }

        $fileType = new FileTypesImages();

        return $fileType->getImage($mime);
    }

    /**
     * @param $mime
     */
    private function getDimensions($mime)
    {
        if (str_contains($mime, 'image')) {
            list($width, $height) = getimagesize($this->storage->path($this->storagePath));

            if (! empty($width) && ! empty($height)) {
                return $width.'x'.$height;
            }
        }

        return false;
    }

    /**
     * @param $timestamp
     */
    public function modificationDate($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->format('Y-m-d H:i:s');
    }
}
