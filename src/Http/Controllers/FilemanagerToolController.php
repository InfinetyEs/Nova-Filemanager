<?php

namespace Infinety\Filemanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Infinety\Filemanager\Http\Services\FileManagerService;
use Laravel\Nova\Http\Requests\NovaRequest;

class FilemanagerToolController extends Controller
{
    /**
     * @var mixed
     */
    protected $service;

    /**
     * @param FileManagerService $filemanagerService
     */
    public function __construct(FileManagerService $filemanagerService)
    {
        $this->service = $filemanagerService;
    }

    /**
     * @param Request $request
     */
    public function getData(Request $request)
    {
        return $this->service->ajaxGetFilesAndFolders($request);
    }

    /**
     * @param Request $request
     */
    public function getDataField($resource, $attribute, NovaRequest $request)
    {
        $filter = $this->getFilemanagerFieldFilter($attribute, $request);

        return $this->service->ajaxGetFilesAndFolders($request, $filter);
    }

    /**
     * @param Request $request
     */
    public function createFolder(Request $request)
    {
        return $this->service->createFolderOnPath($request->folder, $request->current);
    }

    /**
     * @param Request $request
     */
    public function deleteFolder(Request $request)
    {
        return $this->service->deleteDirectory($request->current);
    }

    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        return $this->service->uploadFile($request->file, $request->current, $request->visibility);
    }

    /**
     * @param Request $request
     */
    public function getInfo(Request $request)
    {
        return $this->service->getFileInfo($request->file);
    }

    /**
     * @param Request $request
     */
    public function removeFile(Request $request)
    {
        return $this->service->removeFile($request->file);
    }

    /**
     * @param NovaRequest $request
     */
    private function getFilemanagerFieldFilter($attribute, NovaRequest $request)
    {
        $fields = $request->newResource()->fields($request);
        foreach ($fields as $field) {
            if (isset($field->attribute) && $field->attribute == $attribute) {
                if (isset($field->meta['filterBy'])) {
                    return $field->meta['filterBy'];
                }
            }
        }

        return false;
    }
}
