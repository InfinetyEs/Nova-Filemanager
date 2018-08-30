<?php

namespace Infinety\Filemanager;

use Infinety\Filemanager\Http\Services\FileManagerService;
use Laravel\Nova\Fields\Field;

class FilemanagerField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'filemanager-field';

    /**
     * Set display in details and list as image or icon
     *
     * @return $this
     */
    public function displayAsImage()
    {
        return $this->withMeta(['display' => 'image']);
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

            return $service->getFileInfoAsArray($this->value);
        }

        return [];
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
}
