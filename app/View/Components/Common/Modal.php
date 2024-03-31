<?php

namespace App\View\Components\Common;

use App\View\Components\BaseComponent;

class Modal extends BaseComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view($this->platform() . 'components.modal');
    }
}
