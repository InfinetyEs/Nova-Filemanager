<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Infinety\Filemanager\Http\Services\GetFiles;
use Infinety\Filemanager\Http\Services\NormalizeFile;

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
     * @param Storage $storage
     */
    public function __construct()
    {
        $this->disk = 'public';
        $this->storage = Storage::disk($this->disk);
        $this->exceptFiles = collect([]);
        $this->exceptFolders = collect([]);
        $this->exceptExtensions = collect([]);
        $this->globalFilter = null;
    }

    /**
     * Get ajax request to load files and folders.
     *
     * @param Request $request
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return string
     */
    public function ajaxGetFilesAndFolders(Request $request)
    {
        $folder = $this->cleanSlashes($request->get('folder'));

        //Set relative Path
        $this->setRelativePath($folder);

        $order = $request->get('sort');
        if (!$order) {
            $order = 'type';
        }
        $filter = $request->get('filter');
        if (!$filter) {
            $filter = false;
        }

        $files = $this->getFiles($folder, $order, $filter);

        return response()->json(['files' => $files, 'path' => $this->getPaths($folder)]);
    }

    /**
     * @param $folder
     * @param $path
     */
    public function createFolderOnPath($folder, $currentFolder)
    {
        $path = $currentFolder.'/'.$folder;
        if ($this->storage->makeDirectory($path)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * @param $currentFolder
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
     * @param $file
     * @param $currentFolder
     */
    public function uploadFile($file, $currentFolder)
    {
        if ($this->storage->putFileAs($currentFolder, $file, $file->getClientOriginalName())) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * @param $file
     */
    public function getFileInfo($file)
    {
        $fullPath = $this->storage->path($file);

        $info = new NormalizeFile($this->storage, $fullPath, $file);

        return response()->json($info->toArray());
    }

    /**
     * @param $file
     */
    public function removeFile($files)
    {
        if ($this->storage->delete($files)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
