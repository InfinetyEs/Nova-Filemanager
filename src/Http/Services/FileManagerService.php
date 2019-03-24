<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Infinety\Filemanager\Exceptions\InvalidConfig;

class FileManagerService
{
    use GetFiles;

    /**
     * @var mixed
     */
    protected $storage;

    /**
     * @var mixed
     */
    protected $disk;

    /**
     * @var mixed
     */
    protected $currentPath;

    /**
     * @var mixed
     */
    protected $exceptFiles;

    /**
     * @var mixed
     */
    protected $exceptFolders;

    /**
     * @var mixed
     */
    protected $exceptExtensions;

    /**
     * @var AbstractNamingStrategy
     */
    protected $namingStrategy;

    /**
     * @param Storage $storage
     */
    public function __construct()
    {
        $this->disk = config('filemanager.disk', 'public');

        $this->exceptFiles = collect([]);
        $this->exceptFolders = collect([]);
        $this->exceptExtensions = collect([]);
        $this->globalFilter = null;

        try {
            $this->storage = Storage::disk($this->disk);
        } catch (InvalidArgumentException $e) {
            throw InvalidConfig::driverNotSupported();
        }

        $this->namingStrategy = app()->makeWith(
            config('filemanager.naming', DefaultNamingStrategy::class),
            ['storage' => $this->storage]
        );
    }

    /**
     * Get ajax request to load files and folders.
     *
     * @param Request $request
     *
     * @return json
     */
    public function ajaxGetFilesAndFolders(Request $request, $filter = false)
    {
        $folder = $this->cleanSlashes($request->get('folder'));

        if (! $this->storage->exists($folder)) {
            $folder = '/';
        }

        //Set relative Path
        $this->setRelativePath($folder);

        $order = $request->get('sort');
        if (! $order) {
            $order = config('filemanager.order', 'mime');
        }

        $defaultFilter = config('filemanager.filter', false);

        if ($filter != false) {
            $defaultFilter = $filter;
        }

        $files = $this->getFiles($folder, $order, $defaultFilter);

        $filters = $this->getAvailableFilters($files);

        // $paginate = config('filemanager.paginate', false);

        // if ($paginate != false) {
        //     $filesData = collect($files);
        //     $page = (int) $request->input('page') ?: 1;

        //     $slice = $filesData->slice(($page - 1) * $paginate, $paginate);
        //     $paginator = new LengthAwarePaginator($slice, $files->count(), $paginate);

        //     $parameters = collect(request()->query())->reject(function ($value, $key) {
        //         if ($key == 'folder' || $key == 'filter') {
        //             return false;
        //         }

        //         return true;
        //     })->toArray();

        //     $paginator->appends($parameters);
        //     $files = $paginator->items();

        //     $paginate = $paginator->toArray();
        //     unset($paginate['data']);
        // }

        return response()->json([
            'files'   => $files,
            'path'    => $this->getPaths($folder),
            'filters' => $filters,
            // 'pagination' => ($paginate) ? $paginate : false,
        ]);
    }

    /**
     *  Create a folder on current path.
     *
     * @param $folder
     * @param $path
     *
     * @return  json
     */
    public function createFolderOnPath($folder, $currentFolder)
    {
        $folder = $this->fixDirname($this->fixFilename($folder));

        $path = $currentFolder.'/'.$folder;

        if ($this->storage->has($path)) {
            return response()->json(['error' => __('The folder exist in current path')]);
        }

        if ($this->storage->makeDirectory($path)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Removes a directory.
     *
     * @param $currentFolder
     *
     * @return  json
     */
    public function deleteDirectory($currentFolder)
    {
        if ($this->storage->deleteDirectory($currentFolder)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Upload a file on current folder.
     *
     * @param $file
     * @param $currentFolder
     *
     * @return  json
     */
    public function uploadFile($file, $currentFolder, $visibility)
    {
        $fileName = $this->namingStrategy->name($currentFolder, $file);

        if ($this->storage->putFileAs($currentFolder, $file, $fileName)) {
            $this->setVisibility($currentFolder, $fileName, $visibility);

            return response()->json(['success' => true, 'name' => $fileName]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Get info of file normalized.
     *
     * @param $file
     *
     * @return  json
     */
    public function getFileInfo($file)
    {
        $fullPath = $this->storage->path($file);

        $info = new NormalizeFile($this->storage, $fullPath, $file);

        return response()->json($info->toArray());
    }

    /**
     * Get info of file as Array.
     *
     * @param $file
     *
     * @return  json
     */
    public function getFileInfoAsArray($file)
    {
        if (! $this->storage->exists($file)) {
            return [];
        }

        $fullPath = $this->storage->path($file);

        $info = new NormalizeFile($this->storage, $fullPath, $file);

        return $info->toArray();
    }

    /**
     * Remove a file from storage.
     *
     * @param $file
     *
     * @return  json
     */
    public function removeFile($files)
    {
        if ($this->storage->delete($files)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * @param $file
     */
    public function renameFile($file, $newName)
    {
        $path = str_replace(basename($file), '', $file);

        if ($this->storage->move($file, $path.$newName)) {
            $fullPath = $this->storage->path($path.$newName);

            $info = new NormalizeFile($this->storage, $fullPath, $path.$newName);

            return response()->json(['success' => true, 'data' => $info->toArray()]);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Set visibility to public.
     *
     * @param $folder
     * @param $file
     */
    private function setVisibility($folder, $file, $visibility)
    {
        if ($folder != '/') {
            $folder .= '/';
        }
        $this->storage->setVisibility($folder.$file, $visibility);
    }

    /**
     * @param $files
     */
    private function getAvailableFilters($files)
    {
        $filters = config('filemanager.filters', []);
        if (count($filters) > 0) {
            return collect($filters)->filter(function ($extensions) use ($files) {
                foreach ($files as $file) {
                    if (in_array($file->ext, $extensions)) {
                        return true;
                    }
                }

                return false;
            })->toArray();
        }

        return [];
    }
}
