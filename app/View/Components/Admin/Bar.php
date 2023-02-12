<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Bar extends Component
{
    public $backTo;
    public $title;
    public $buttonTo;
    public $buttonText;

    public function __construct($backTo = '', $title = '', $buttonTo = '', $buttonText = ''){
        $this->backTo = $backTo;
        $this->title = $title;
        $this->buttonTo = $buttonTo;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.bar');
    }
}
