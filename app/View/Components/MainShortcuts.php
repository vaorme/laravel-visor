<?php

namespace App\View\Components;

use App\Models\Manga;
use App\Models\Shortcut;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class MainShortcuts extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string{
        $mangas = Manga::get();
        $user = Auth::user();
        $viewData = [
            'mangas' => $mangas,
        ];
        if(isset($user->shortcutMangas)){
            $viewData['shortcuts'] = $user->shortcutMangas;
        }
        return view('components.main-shortcuts', $viewData);
    }
}
