<?php

namespace Infinety\Filemanager;

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
     * Set the hues that may be selected by the color picker.
     *
     * @param  array  $hues
     * @return $this
     */
    public function disk(array $disk)
    {
        return $this->withMeta(['disk' => $disk]);
    }
}
