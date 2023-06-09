<?php

namespace Infinety\Filemanager\Http\Services;

use Illuminate\Support\Facades\Artisan;
use App\Repositories\Kiosk\KioskRepository;
use App\Repositories\Catalogue\CatalogueRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Infinety\Filemanager\Events\FileRemoved;
use Infinety\Filemanager\Events\FileUploaded;
use Infinety\Filemanager\Events\FolderRemoved;
use Infinety\Filemanager\Events\FolderUploaded;
use Infinety\Filemanager\Http\Exceptions\InvalidConfig;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;

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

    /** @var string  */
    protected $mimeType;

    /** @var string  */
    protected $webpPath;

    /** @var mixed */
    protected $storageWebp;

    /** @var string  */
    protected $webpFolder;

    /** @var string */
    protected $centralizedImagesFolderName;

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
        $this->mimeType = "webp";
        $this->webpPath = "app/webp/";
        $this->storageWebp = Storage::disk('local');
        $this->webpFolder = "/webp/";
        $this->centralizedImagesFolderName = config('filemanager.folder_centralized_images_name');

        if (!$this->storageWebp->exists($this->webpFolder)) {
            $this->storageWebp->makeDirectory($this->webpFolder, 0775, true);
        }

        try {
            $this->storage = Storage::disk($this->disk);
        } catch (InvalidArgumentException $e) {
            throw InvalidConfig::driverNotSupported();
        }

        $this->storagePath = $this->storage->path('');

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
    public function ajaxGetFilesAndFolders(Request $request)
    {
        $folder = $this->cleanSlashes($request->get('folder'));
        $kioskRepository = app(KioskRepository::class);
        $catalogueRepository = app(CatalogueRepository::class);
        $user = Auth::user();
        $kiosks = [];
        $catalogues = [];

        if (!$this->folderExists($folder)) {
            if (!$user->isRoot()) {
                abort(404);
            }

            $folder = '/';
        }

        $this->setRelativePath($folder);

        $order = $request->get('sort');
        if (! $order) {
            $order = config('filemanager.order', 'mime');
        }

        $filter = $request->get('filter', config('filemanager.filter', false));

        $files = $this->getFiles($folder, $order, $filter);

        $filters = $this->getAvailableFilters($files);

        $parent = (object) [];

        if ($files->count() > 0) {
            $folders = $files->filter(function ($file) {
                return $file->type == 'dir';
            });

            if ($folder !== '/') {
                $parent = $this->generateParent($folder);
            }
        }

        if (str_starts_with(strtolower($folder), strtolower(config("filemanager.folder_catalogue_name")))) {
            foreach ($catalogueRepository->getCataloguesForUser($user) as $catalogue) {
                array_push($catalogues, $catalogue->name);
            }

            $files = $files->filter(function ($value, $key) use ($catalogues, $user) {
                if ($user->isRoot()) {
                    return true;
                }

                return Str::contains($value->path, $catalogues);
            });

            if ($files->count() === 0) {
                $parent = (object) [];
            }
        } elseif (str_starts_with(strtolower($folder), strtolower(config("filemanager.folder_kiosk_name")))) {
            foreach ($kioskRepository->getKiosksForUser($user) as $kiosk) {
                array_push($kiosks, $kiosk->name);
            }

            $files = $files->filter(function ($value, $key) use ($kiosks, $user) {
                if ($user->isRoot()) {
                    return true;
                }

                return Str::contains($value->path, $kiosks);
            });

            if ($files->count() === 0) {
                $parent = (object) [];
            }
        } elseif (!$user->isRoot()) {
            abort(404);
        }

        return response()->json([
            'files'   => $files->values(),
            'path'    => $this->getPaths($folder),
            'filters' => $filters,
            'buttons' => $this->getButtons(),
            'parent'  => $parent,
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
     * @param string $path
     *
     * @return  json
     */
    public function deleteDirectory($path)
    {
        if ($this->storage->deleteDirectory($path)) {
            event(new FolderRemoved($this->storage, $path));

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
    public function uploadFile($file, $currentFolder, $visibility, $uploadingFolder = false, array $rules = [])
    {
        $screenSaverFolderName = config('filemanager.folder_screen_saver_name');
        $sharedScreenSaverFolderName = config('filemanager.folder_shared_screen_saver_name');

        if (preg_match('/^image\//', $file->getMimeType())) {
            if (strpos($currentFolder, $screenSaverFolderName) !== false) {
                $pases = Validator::make(['file' => $file], [
                    'file' => 'max:5000|dimensions:max_width=1600px,max_height=900px',
                ])->validate();
            } else if (strpos($currentFolder, $sharedScreenSaverFolderName) !== false) {
                $pases = Validator::make(['file' => $file], [
                    'file' => 'max:5000|dimensions:max_width=1600px,max_height=900px',
                ])->validate();
            } else {
                $pases = Validator::make(['file' => $file], [
                    'file' => 'max:5000',
                ])->validate();
            }
        } else if (preg_match('/^video\//', $file->getMimeType())) {
            $pases = Validator::make(['file' => $file], [
                'file' => 'max:10240',
            ])->validate();
        } else if (count($rules) > 0) {
            $pases = Validator::make(['file' => $file], [
                'file' => $rules,
            ])->validate();
        }

        $fileName = $this->namingStrategy->name($currentFolder, $file);

        if ($this->storage->putFileAs($currentFolder, $file, $fileName)) {
            $this->setVisibility($currentFolder, $fileName, $visibility);

            if (! $uploadingFolder) {
                $this->checkJobs($this->storage, $currentFolder.$fileName);
                event(new FileUploaded($this->storage, $currentFolder.$fileName));
            }

            if (preg_match('/^image\//', $file->getMimeType())) {
                $webpImg = pathinfo($currentFolder . $fileName, PATHINFO_FILENAME) . ".$this->mimeType";
                $webpImgPath = storage_path($this->webpPath . $webpImg);

                if ($this->storageWebp->exists($webpImg)) {
                    $this->storageWebp->delete($webpImg);
                }

                $image = Image::make($this->storagePath . '/' . $currentFolder . $fileName)
                    ->encode($this->mimeType, 70)
                    ->save($webpImgPath);

                if (str_starts_with($currentFolder, $this->centralizedImagesFolderName)) {
                    $base64 = base64_encode($image);

                    Artisan::call('centralized_images:sync:couchdb', [
                        'image' => [
                            'docName' => "{$this->centralizedImagesFolderName}/{$fileName}",
                            'imageB64' => "data:{$this->mimeType};base64, {$base64}"
                        ]
                    ]);
                }
            }

            return response()->json(['success' => true, 'name' => $fileName]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * @param $file
     * @return mixed
     */
    public function downloadFile($file)
    {
        if (! config('filemanager.buttons.download_file')) {
            return response()->json(['success' => false, 'message' => 'File not available for Download'], 403);
        }

        if ($this->storage->has($file)) {
            return $this->storage->download($file);
        } else {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
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
        try {
            $info = new NormalizeFile($this->storage, $fullPath, $file);

            return response()->json($info->toArray());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
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
     * @param string $file
     * @param string $type
     *
     * @return  json
     */
    public function removeFile($file)
    {
        if ($this->storage->delete($file)) {
            $webpImg = pathinfo($file, PATHINFO_FILENAME) . ".$this->mimeType";

            $this->storageWebp->delete($this->webpFolder . $webpImg);

            event(new FileRemoved($this->storage, $file));

            if (str_starts_with($file, $this->centralizedImagesFolderName)) {
                Artisan::call('centralized_images:sync:couchdb', [
                    'image' => [
                        'docName' => "{$file}",
                    ],
                    '--delete' => true,
                ]);
            }

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
        $mimeType = json_decode($this->getFileInfo($file)->content())->mime;

        try {
            if ($this->storage->move($file, $path.$newName)) {
                $fullPath = $this->storage->path($path.$newName);

                if (preg_match('/^image\//', $mimeType)) {
                    $oldWebpImg = pathinfo($file, PATHINFO_FILENAME) . ".$this->mimeType";

                    if ($this->storageWebp->exists($this->webpFolder . $oldWebpImg)) {
                        $this->storageWebp->delete($this->webpFolder . $oldWebpImg);
                    }
                }

                $info = new NormalizeFile($this->storage, $fullPath, $path.$newName);

                if (preg_match('/^image\//', $mimeType)) {
                    $newWebpImg = pathinfo($newName, PATHINFO_FILENAME) . ".$this->mimeType";
                    $webpImgPath = storage_path($this->webpPath . $newWebpImg);

                    if ($this->storageWebp->exists($this->webpFolder . $newWebpImg)) {
                        $this->storageWebp->delete($this->webpFolder . $newWebpImg);
                    }

                    $image = Image::make($this->storagePath . '/' . $path . $newName)
                        ->encode($this->mimeType, 70)
                        ->save($webpImgPath);

                    if (str_starts_with($path.$newName, $this->centralizedImagesFolderName)) {
                        $base64 = base64_encode($image);

                        Artisan::call('centralized_images:sync:couchdb', [
                            'image' => [
                                'docName' => $file,
                                'newName' => "{$this->centralizedImagesFolderName}/{$newName}",
                                'imageB64' => "data:{$this->mimeType};base64, {$base64}"
                            ]
                        ]);
                    }
                }

                return response()->json(['success' => true, 'data' => $info->toArray()]);
            } else {
                return response()->json(false);
            }
        } catch (\Exception $e) {
            return response()->json(false);
        }
    }

    /**
     * Move file.
     *
     * @param   string  $oldPath
     * @param   string  $newPath
     *
     * @return  json
     */
    public function moveFile($oldPath, $newPath)
    {
        if ($this->storage->move($oldPath, $newPath)) {
            if (str_starts_with($oldPath, $this->centralizedImagesFolderName)
                && str_starts_with($newPath, $this->centralizedImagesFolderName)) {
                $image = $this->storage->get($newPath);
                $base64 = base64_encode($image);

                Artisan::call('centralized_images:sync:couchdb', [
                    'image' => [
                        'docName' => $oldPath,
                        'newName' => $newPath,
                        'imageB64' => "data:{$this->mimeType};base64, {$base64}"
                    ]
                ]);
            }

            if (str_starts_with($oldPath, $this->centralizedImagesFolderName)
                && !str_starts_with($newPath, $this->centralizedImagesFolderName)) {
                Artisan::call('centralized_images:sync:couchdb', [
                    'image' => [
                        'docName' => $oldPath,
                    ],
                    '--delete' => true,
                ]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(false);
    }

    /**
     * Folder uploaded event.
     *
     * @param   string  $path
     *
     * @return  json
     */
    public function folderUploadedEvent($path)
    {
        event(new FolderUploaded($this->storage, $path));

        return response()->json(['success' => true]);
    }

    /**
     * @param $folder
     */
    private function folderExists($folder)
    {
        $directories = $this->storage->directories(dirname($folder));

        $directories = collect($directories)->map(function ($folder) {
            return basename($folder);
        });

        return in_array(basename($folder), $directories->toArray());
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

    private function getButtons()
    {
        return config('filemanager.buttons', [
            'create_folder'   => true,
            'upload_button'   => true,
            'select_multiple' => true,
            'upload_drag'     => true,
            'rename_folder'   => true,
            'delete_folder'   => true,
            'rename_file'     => true,
            'delete_file'     => true,
        ]);
    }

    /**
     * @param $currentPath
     * @param $fileName
     */
    private function checkJobs($storage, $filePath)
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        //$availableJobs
        $availableJobs = collect(config('filemanager.jobs', []));

        if (count($availableJobs)) {
            // Filters
            $filters = config('filemanager.filters', []);
            $filters = array_change_key_case($filters);

            $find = collect($filters)->filter(function ($extensions, $key) use ($ext) {
                if (in_array($ext, $extensions)) {
                    return true;
                }
            });

            $filterFind = array_key_first($find->toArray());

            if ($jobClass = $availableJobs->get($filterFind)) {
                $job = new $jobClass($storage, $filePath);

                if ($customQueue = config('filemanager.queue_name')) {
                    $job->onQueue($customQueue);
                }

                app(Dispatcher::class)->dispatch($job);
            }
        }
    }
}
