<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $this->disk = env('FILEMANAGER_DISK', 'public');
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
     * @return json
     */
    public function ajaxGetFilesAndFolders(Request $request)
    {
        $folder = $this->cleanSlashes($request->get('folder'));

        if (! $this->storage->exists($folder)) {
            $folder = '/';
        }

        //Set relative Path
        $this->setRelativePath($folder);

        $order = $request->get('sort');
        if (! $order) {
            $order = 'mime';
        }
        $filter = $request->get('filter');
        if (! $filter) {
            $filter = false;
        }

        $files = $this->getFiles($folder, $order, $filter);

        return response()->json(['files' => $files, 'path' => $this->getPaths($folder)]);
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
    public function uploadFile($file, $currentFolder)
    {
        $fileName = $this->checkFileExists($currentFolder, $file);

        if ($this->storage->putFileAs($currentFolder, $file, $fileName)) {
            $this->setVisibility($currentFolder, $fileName);

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
     * @param $filePath
     */
    private function checkFileExists($currentFolder, $file)
    {
        if ($this->storage->has($currentFolder.'/'.$file->getClientOriginalName())) {
            $random = str_random(7);
            $newName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.mb_strtolower($random);

            return $newName.'.'.$file->getClientOriginalExtension();
        }

        return $file->getClientOriginalName();
    }

    /**
     * Set visibility to public.
     *
     * @param $folder
     * @param $file
     */
    private function setVisibility($folder, $file)
    {
        if ($folder != '/') {
            $folder .= '/';
        }
        $this->storage->setVisibility($folder.$file, 'public');
    }
}
