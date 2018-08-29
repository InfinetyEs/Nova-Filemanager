<?php

namespace Infinety\Filemanager;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool as BaseTool;

class FilemanagerTool extends BaseTool
{

    /**
     * @param $options
     * @param $component
     */
    public function __construct($component = null)
    {
        // $this->meta = $options;

        parent::__construct();
    }

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-filemanager', __DIR__.'/../dist/js/tool.js');
        // Nova::style('nova-filemanager', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-filemanager::navigation');
    }
}
