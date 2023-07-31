<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MangaLoopItem extends Component{
    public $item;
    
    public function __construct($item){
        $this->item = $item;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.manga-loop-item');
    }
}
