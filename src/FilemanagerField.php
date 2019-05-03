<?php

namespace Infinety\Filemanager;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Contracts\Cover;
use Infinety\Filemanager\Http\Services\FileManagerService;

class FilemanagerField extends Field implements Cover
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'filemanager-field';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta(['visibility' => 'public']);
    }

    /**
     * Set display in details and list as image or icon.
     *
     * @return $this
     */
    public function displayAsImage()
    {
        return $this->withMeta(['display' => 'image']);
    }

    /**
     * Set current folder for the field.
     *
     * @param   string  $folderName
     *
     * @return  $this
     */
    public function folder($folderName)
    {
        $folder = is_callable($folderName) ? call_user_func($folderName) : $folderName;

        return $this->withMeta(['folder' => $folder, 'home' => $folder]);
    }

    /**
     * Set filter for the field.
     *
     * @param   string  $folderName
     *
     * @return  $this
     */
    public function filterBy($filter)
    {
        $deafaultFilters = config('filemanager.filters', []);

        if (count($deafaultFilters) > 0) {
            $filters = array_change_key_case($deafaultFilters);

            if (isset($filters[$filter])) {
                $filteredExtensions = $filters[$filter];

                return $this->withMeta(['filterBy' => $filter]);
            }
        }

        return $this;
    }

    /**
     * Set display in details and list as image or icon.
     *
     * @return $this
     */
    public function privateFiles()
    {
        return $this->withMeta(['visibility' => 'private']);
    }

    /**
     * Resolve the thumbnail URL for the field.
     *
     * @return string|null
     */
    public function resolveInfo()
    {
        if ($this->value) {
            $service = new FileManagerService();

            $data = $service->getFileInfoAsArray($this->value);

            if (empty($data)) {
                return [];
            }

            return $this->fixNameLabel($data);
        }

        return [];
    }

    /**
     * Resolve the thumbnail URL for the field.
     *
     * @return string|null
     */
    public function resolveThumbnailUrl()
    {
        if ($this->value) {
            $service = new FileManagerService();

            $data = $service->getFileInfoAsArray($this->value);

            if (empty($data)) {
                return;
            }

            return $data['url'];
        }
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge($this->resolveInfo(), $this->meta);
    }

    /**
     * FIx name label.
     *
     * @param array $data
     *
     * @return array
     */
    private function fixNameLabel(array $data): array
    {
        $data['filename'] = $data['name'];
        unset($data['name']);

        return $data;
    }
}
