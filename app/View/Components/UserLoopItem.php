<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserLoopItem extends Component{
    public $item;
    
    public function __construct($item){
        $this->item = $item;
        
    }
    
    public function render(): View|Closure|string
    {
        return view('components.user-loop-item');
    }
}
