<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }

    public function index(Request $request){
        $loop = Slider::get();
        $mangas = Manga::get();
        $viewData = [
            'loop' => $loop,
            'mangas' => $mangas
        ];
        if(isset($request->id)){
            $user = Auth::user();
            if($user->can('tags.edit')){
                $edit = Slider::find($request->id);
                $viewData['edit'] = $edit;
            }else{
                return abort(404);
            }
        }
        return view('admin.slider.index', $viewData);
    }
    public function store(Request $request){
        $request->validate([
            'manga_id' => ['required', 'unique:slider'],
            'logo' => ['dimensions:max_width=512', 'max:400', 'mimes:webp,jpg,jpeg,png,gif'],
            'background' => ['required', 'dimensions:max_width=1920', 'max:680', 'mimes:webp,jpg,jpeg,png,gif']
        ]);

        $store = new Slider;

        $store->manga_id = $request->manga_id;
        $store->description = $request->description;
        $manga = Manga::find($request->manga_id);
        if(isset($request->logo)){
            $extension = $originalName = $request->file('logo')->extension();
            $pathAvatar = $request->file('logo')->storeAs('images/slider', $manga->slug.'-logo.'.$extension, $this->disk);
			$store->logo = 'images/slider/'.$manga->slug.'-logo.'.$extension;
        }
        if(isset($request->background)){
            $extension = $originalName = $request->file('background')->extension();
            $pathAvatar = $request->file('background')->storeAs('images/slider', $manga->slug.'-bg.'.$extension, $this->disk);
			$store->background = 'images/slider/'.$manga->slug.'-bg.'.$extension;
        }

        if($store->save()){
            return redirect()->route('slider.index')->with('success', 'Elemento correctamente');
        }
        return redirect()->route('slider.index')->with('error', 'Ups, se complico la cosa');
    }
    public function edit($id){
        $edit = Slider::find($id);
        return view('admin.categories.edit', ['edit' => $edit]);
    }
    public function update(Request $request, $id){
        $request->validate([
            'manga_id' => ['required'],
            'logo' => ['dimensions:max_width=512', 'max:400', 'mimes:webp,jpg,jpeg,png,gif'],
            'background' => ['dimensions:max_width=1920', 'max:680', 'mimes:webp,jpg,jpeg,png,gif']
        ]);

        $update = Slider::find($id);

        $update->manga_id = $request->manga_id;
        $update->description = $request->description;
        $manga = Manga::find($request->manga_id);
        if(isset($request->logo)){
            if(!empty($update->logo)){
                Storage::delete($update->logo);
            }
            $extension = $originalName = $request->file('logo')->extension();
            $pathAvatar = $request->file('logo')->storeAs('images/slider', $manga->slug.'-logo.'.$extension, $this->disk);
			$update->logo = 'images/slider/'.$manga->slug.'-logo.'.$extension;
        }
        if(isset($request->background)){
            if(!empty($update->background)){
                Storage::delete($update->background);
            }
            $extension = $originalName = $request->file('background')->extension();
            $pathAvatar = $request->file('background')->storeAs('images/slider', $manga->slug.'-bg.'.$extension, $this->disk);
			$update->background = 'images/slider/'.$manga->slug.'-bg.'.$extension;
        }

        if($update->save()){
            return redirect()->route('slider.index')->with('success', 'Elemento correctamente');
        }
        return redirect()->route('slider.index')->with('error', 'Ups, se complico la cosa');
    }
    public function destroy($id){
        $delete = Slider::find($id);
        if(!empty($delete->background)){
            Storage::disk($this->disk)->delete($delete->background);
        }
        if(!empty($delete->logo)){
            Storage::disk($this->disk)->delete($delete->logo);
        }
        if($delete->delete()){
            $response['msg'] = "Elemento eliminada correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
