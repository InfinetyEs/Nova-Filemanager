<?php

namespace Infinety\Filemanager\Http\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait GetFiles
{
    use FileFunctions;

    /**
     * @param $folder
     * @param $order
     * @param $filter
     */
    public function getFiles($folder, $order, $filter = false)
    {
        $filesData = $this->storage->listContents($folder);
        $filesData = $this->normalizeFiles($filesData);

        $files = [];

        $minutes = env('FILEMANAGER_CACHE', 5);

        foreach ($filesData as $file) {
            $id = $this->generateId($file);

            $fileData = Cache::remember($id, $minutes, function () use ($file, $id) {
                return $this->getFileData($file, $id);
            });

            $files[] = $fileData;
        }
        $files = collect($files);
        if ($filter != false) {
            $files = $this->filterData($files, $filter);
        }

        return $this->orderData($files, $order);
    }

    /**
     * @param $file
     * @param $id
     */
    public function getFileData($file, $id)
    {
        if (! $this->isDot($file) && ! $this->exceptExtensions->contains($file['extension']) && ! $this->exceptFolders->contains($file['basename']) && ! $this->exceptFiles->contains($file['basename']) && $this->accept($file)) {
            $fileInfo = [
                'id'         => $id,
                'name'       => trim($file['basename']),
                'path'       => $this->cleanSlashes($file['path']),
                'type'       => $file['type'],
                'mime'       => $this->getFileType($file),
                'size'       => ($file['size'] != 0) ? $file['size'] : 0,
                'size_human' => ($file['size'] != 0) ? $this->formatBytes($file['size'], 0) : 0,
                'thumb'      => $this->getThumbFile($file),
                'asset'      => $this->cleanSlashes($this->storage->url($file['basename'])),
                'can'        => true,
                'loading'    => false,
            ];

            if (isset($file['timestamp'])) {
                $fileInfo['last_modification'] = $file['timestamp'];
            }

            if ($fileInfo['mime'] == 'image') {
                list($width, $height) = $this->getImageDimesions($file);
                $fileInfo['dimensions'] = $width.'x'.$height;
            }

            return (object) $fileInfo;
        }
    }

    /**
     * Filter data by custom type.
     *
     * @param $files
     * @param $filter
     *
     * @return mixed
     */
    public function filterData($files, $filter)
    {
        $folders = $files->where('type', 'dir');
        $items = $files->where('type', 'file');
        $filtered = $items->filter(function ($item) use ($filter) {
            switch ($filter) {
                case 'image':
                    if (str_contains($item->mime, 'image')) {
                        return $item;
                    }
                    break;
                case 'audio':
                    if (str_contains($item->mime, 'audio')) {
                        return $item;
                    }
                    break;
                case 'video':
                    if (str_contains($item->mime, 'video')) {
                        return $item;
                    }
                    break;
                case 'documents':
                    if (str_contains($item->mime, 'word') || str_contains($item->mime, 'excel') || str_contains($item->mime, 'pdf') || str_contains($item->mime, 'plain') || str_contains($item->mime, 'rtf') || str_contains($item->mime, 'text')) {
                        return $item;
                    }
                    break;
                case 'all':
                    return $item;
                    break;
            }
        });

        return $folders->merge($filtered);
    }

    /**
     * Order files and folders.
     *
     * @param $files
     * @param $order
     *
     * @return mixed
     */
    public function orderData($files, $order, $direction = 'asc')
    {
        $folders = $files->where('type', 'dir');
        $items = $files->where('type', 'file');

        if ($order == 'size') {
            $folders = $folders->sortByDesc($order);
            $items = $items->sortByDesc($order);
        } else {
            if ($direction == 'asc') {
                // mb_strtolower to fix order by alpha
                $folders = $folders->sortBy('name')->sortBy(function ($item) use ($order) {
                    return mb_strtolower($item->{$order});
                })->values();

                $items = $items->sortBy('name')->sortBy(function ($item) use ($order) {
                    return mb_strtolower($item->{$order});
                })->values();
            } else {
                // mb_strtolower to fix order by alpha
                $folders = $folders->sortByDesc(function ($item) use ($order) {
                    return mb_strtolower($item->{$order});
                })->values();

                $items = $items->sortByDesc(function ($item) use ($order) {
                    return mb_strtolower($item->{$order});
                })->values();
            }
        }

        return $folders->merge($items);
    }

    /**
     * Generates an id based on file.
     *
     * @param   array  $file
     *
     * @return  string
     */
    public function generateId($file)
    {
        if (isset($file['timestamp'])) {
            return md5($this->disk.'_'.trim($file['basename']).$file['timestamp']);
        }

        return md5($this->disk.'_'.trim($file['basename']));
    }

    /**
     * @param $file
     *
     * @return bool|string
     */
    public function getFileType($file)
    {
        $mime = $this->storage->getMimetype($file['path']);

        if (str_contains($mime, 'directory')) {
            return 'dir';
        }

        if (str_contains($mime, 'image')) {
            return 'image';
        }

        if (str_contains($mime, 'pdf')) {
            return 'pdf';
        }

        if (str_contains($mime, 'audio')) {
            return 'audio';
        }

        if (str_contains($mime, 'video')) {
            return 'video';
        }

        if (str_contains($mime, 'zip')) {
            return 'file';
        }

        if (str_contains($mime, 'rar')) {
            return 'file';
        }

        if (str_contains($mime, 'octet-stream')) {
            return 'file';
        }

        if (str_contains($mime, 'excel')) {
            return 'text';
        }

        if (str_contains($mime, 'word')) {
            return 'text';
        }

        if (str_contains($mime, 'css')) {
            return 'text';
        }

        if (str_contains($mime, 'javascript')) {
            return 'text';
        }

        if (str_contains($mime, 'plain')) {
            return 'text';
        }

        if (str_contains($mime, 'rtf')) {
            return 'text';
        }

        if (str_contains($mime, 'text')) {
            return 'text';
        }

        return false;
    }

    /**
     * Return the Type of file.
     *
     * @param $file
     *
     * @return bool|string
     */
    public function getThumb($file, $folder = false)
    {
        $mime = $this->storage->getMimetype($file['path']);

        if (str_contains($mime, 'directory')) {
            return false;
        }

        if (str_contains($mime, 'image')) {
            // return $this->storage->url($file['basename']);
            // if ($folder) {
            //     return '/'.$folder.DIRECTORY_SEPARATOR.$file['basename'];
            // }
            if ( env('FILEMANAGER_DISK_DRIVER', $this->disk)  == 's3') {
                return $this->storage->url($file['path']);
            }

            return $folder.'/'.$file['basename'];
        }

        $fileType = new FileTypesImages();

        return $fileType->getImage($mime);
    }

    /**
     * Get image dimensions for files.
     *
     * @param $file
     */
    public function getImageDimesions($file)
    {
        if ($this->disk == 'public') {
            return getimagesize($this->storage->path($file['path']));
        }

        if ($this->disk == 's3') {
            return false;

            return $this->getImageDimesionsFromCloud($file);
        }

        return false;
    }

    /**
     * Get image dimensions from cloud.
     *
     * @param $file
     */
    public function getImageDimesionsFromCloud($file)
    {
        try {
            $client = new Client();

            $response = $client->get($this->storage->url($file['path']), ['stream' => true]);
            $image = imagecreatefromstring($response->getBody()->getContents());
            $dims = [imagesx($image), imagesy($image)];
            imagedestroy($image);

            return $dims;
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function getThumbFile($file)
    {
        return $this->cleanSlashes($this->getThumb($file, $this->currentPath));
    }

    /**
     * @param $files
     */
    public function normalizeFiles($files)
    {
        foreach ($files as $key => $file) {
            if (! isset($file['extension'])) {
                $files[$key]['extension'] = null;
            }
            if (! isset($file['size'])) {
                $files[$key]['size'] = null;
            }
        }

        return $files;
    }

    /**
     * Set Relative Path.
     *
     * @param $folder
     */
    public function setRelativePath($folder)
    {
        $defaultPath = $this->storage->path('/');

        $publicPath = str_replace($defaultPath, '', $folder);

        if ($folder != '/') {
            $this->currentPath = $this->getAppend().'/'.$publicPath;
        } else {
            $this->currentPath = $this->getAppend().$publicPath;
        }
    }

    /**
     * Get Append to url.
     *
     * @return mixed|string
     */
    public function getAppend()
    {
        if (config('filemanager.appendUrl') != null) {
            return config('filemanager.appendUrl');
        } else {
            return '/storage';
        }
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function accept($file)
    {
        return '.' !== substr($file['basename'], 0, 1);
    }

    /**
     * Check if file is Dot.
     *
     * @param   string   $file
     *
     * @return  bool
     */
    public function isDot($file)
    {
        if (starts_with($file['basename'], '.')) {
            return true;
        }

        return false;
    }

    /**
     * @param $currentFolder
     */
    public function getPaths($currentFolder)
    {
        $defaultPath = $this->cleanSlashes($this->storage->path('/'));
        $currentPath = $this->cleanSlashes($this->storage->path($currentFolder));
        $paths = $currentPath;

        if ($defaultPath != '/') {
            $paths = str_replace($defaultPath, '', $currentPath);
        }

        $paths = collect(explode('/', $paths))->filter();

        $goodPaths = collect([]);

        foreach ($paths as $path) {
            $goodPaths->push(['name' => $path, 'path' => $this->recursivePaths($path, $paths)]);
        }

        return $goodPaths->reverse();
    }

    /**
     * @param $pathCollection
     */
    public function recursivePaths($name, $pathCollection)
    {
        return str_before($pathCollection->implode('/'), $name).$name;
    }
}
