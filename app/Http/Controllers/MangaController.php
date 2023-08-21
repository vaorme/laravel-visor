<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Manga;
use App\Models\MangaBookStatus;
use App\Models\MangaDemography;
use App\Models\MangaType;
use App\Models\MangaHasCategory;
use App\Models\MangaHasTag;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use ManipulateImage;

class MangaController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }

    public function index(Request $request){
        $request->validate([
            'status' => ['max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        $status = ($request->status == null)? 'published': $request->status;
        $loop = Manga::where('status', '=', $status);
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%');
            });
        }
        return view('admin.manga.index', ['loop' => $loop->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
        $mangaTypes = MangaType::get();
        $mangaBookStatus = MangaBookStatus::get();
        $mangaDemographies = MangaDemography::get();
        $categories = Category::get();
        $tags = Tag::get();
        return view('admin.manga.create', [
            'users' => $users,
            'tags' => $tags,
            'categories' => $categories,
            'manga_types' => $mangaTypes,
            'manga_book_status' => $mangaBookStatus,
            'manga_demographies' => $mangaDemographies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:240'],
            'slug' => ['required', 'max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);

        $mangaExists = Manga::orderBy('id', 'DESC');
        $count = "";
        if($mangaExists->exists()){
            $order = $mangaExists->first()->toArray();
            $count = $order['order'] + 1;
        }else{
            $count = 1;
        }

        $imageFile = $request->file('featured_image');
        $mangaSlug = $request->slug;
        $fileName = $imageFile->hashName();
        if($imageFile){
            Storage::disk($this->disk)->makeDirectory('covers/manga/'.$mangaSlug.'/cover');

            $img = ManipulateImage::make($imageFile->path())->resize(458, null, function ($constraint) {
                $constraint->aspectRatio();
            })->fit(458, 646);
            $stored = Storage::disk($this->disk)->put('covers/manga/'.$mangaSlug.'/cover/'.$fileName, $img->stream()->__toString());
            //$stored = Storage::disk($this->disk)->putFile('manga/'.$mangaSlug.'/cover', new File($img->stream()));    
        }

        // Fields
        $manga = new Manga;
        $manga->order = $count;
        if(!empty($imageFile)){
            $manga->featured_image = 'covers/manga/'.$mangaSlug.'/cover/'.$fileName;
        }
        $manga->name = $request->name;
        $manga->alternative_name = $request->alternative_name;
        $manga->slug = $mangaSlug;
        $manga->description = $request->description;
        $manga->status = $request->status;
        $manga->release_date = $request->release_date;
        $manga->user_id = $request->user_id;
        $manga->type_id = $request->type_id;
        $manga->book_status_id = $request->book_status_id;
        $manga->demography_id = $request->demography_id;
        $manga->new_chapters_time = $request->new_chapters_time;
        $manga->new_chapters_date = $request->new_chapters_date;

        if($manga->save()){
            Cache::forget('manga_shortcuts');
            Cache::forget('most_viewed');
            if(isset($request->categories)){
                foreach($request->categories as $cat){
                    $category = new MangaHasCategory;
                    $category->manga_id = $manga->id;
                    $category->category_id = $cat;
                    $category->save();
                }
            }
            if($request->tags){
                $deleteTags = MangaHasTag::where('manga_id', $manga->id)->delete();
                $originalTags = explode(',', $request->tags);
                $tags = $this->removeSpecialAccents($request->tags);
                $tags = explode(',', $tags);
                for ($i=0; $i < count($tags); $i++) {
                    $slug = $this->sanitizeText($tags[$i]);
                    $slugExists = Tag::where('slug', $slug)->exists();
                    if($slugExists){
                        $hasTag = new MangaHasTag;
                        $hasTag->manga_id = $manga->id;
                        $hasTag->tag_id = $slugExists->id;
                        $hasTag->save();
                    }else{
                        $createTag = new Tag;
                        $originalName = preg_replace('/[^A-Za-z0-9. -]/', ' ', $originalTags[$i]);
                        $createTag->name = trim($originalName);
                        $createTag->slug = strtolower($slug);
                        if($createTag->save()){
                            $hasTag = new MangaHasTag;
                            $hasTag->manga_id = $manga->id;
                            $hasTag->tag_id = $createTag->id;
                            $hasTag->save();
                        }
                    }
                }
            }

            return redirect()->route('manga.edit', ['id' => $manga->id])->with('success', 'Manga creado correctamente');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $manga = Manga::find($id);
        $users = User::get();
        $mangaTypes = MangaType::get();
        $mangaBookStatus = MangaBookStatus::get();
        $mangaDemographies = MangaDemography::get();
        $categories = Category::get();
        $chapters = Chapter::where('manga_id', $id)->get();
        $mangaHasCategories = MangaHasCategory::where('manga_id', '=', $id)->join('categories', 'categories.id', '=', 'manga_has_categories.category_id')
        ->get(['categories.id', 'categories.name']);
        $mangaHasTags = MangaHasTag::where('manga_id', '=', $id)->join('tags', 'tags.id', '=', 'manga_has_tags.tag_id')
        ->get(['tags.id', 'tags.name']);

        return view('admin.manga.edit', [
            'manga' => $manga,
            'users' => $users,
            'categories' => $categories,
            'manga_types' => $mangaTypes,
            'manga_book_status' => $mangaBookStatus,
            'manga_demographies' => $mangaDemographies,
            'manga_categories' => $mangaHasCategories,
            'manga_tags' => $mangaHasTags,
            'chapters' => $chapters
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'max:240'],
            'slug' => ['required', 'max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        $manga = Manga::find($id);

        $imageFile = $request->file('featured_image');
        $mangaSlug = $request->slug;
        if($imageFile){
            Storage::disk($this->disk)->deleteDirectory('covers/manga/'.$mangaSlug.'/cover');
            Storage::disk($this->disk)->makeDirectory('covers/manga/'.$mangaSlug.'/cover');

            $img = ManipulateImage::make($imageFile->path())->resize(458, null, function ($constraint) {
                $constraint->aspectRatio();
            })->fit(458, 646);
            $fileName = $imageFile->hashName();
            Storage::disk($this->disk)->put('covers/manga/'.$mangaSlug.'/cover/'.$fileName, $img->stream()->__toString());

            $manga->featured_image = 'covers/manga/'.$mangaSlug.'/cover/'.$fileName;
        }

        $manga->name = $request->name;
        $manga->alternative_name = $request->alternative_name;
        $manga->slug = $mangaSlug;
        $manga->description = $request->description;
        $manga->status = $request->status;
        $manga->release_date = $request->release_date;
        $manga->user_id = $request->user_id;
        $manga->type_id = $request->type_id;
        $manga->book_status_id = $request->book_status_id;
        $manga->demography_id = $request->demography_id;
        $manga->new_chapters_time = $request->new_chapters_time;
        $manga->new_chapters_date = $request->new_chapters_date;

        if(isset($request->categories)){
            // Removemos las categorias antiguas por el manga id
            $deleteCategories = MangaHasCategory::where('manga_id', $id)->delete();
            foreach($request->categories as $cat){
                $category = new MangaHasCategory;
                $category->manga_id = $manga->id;
                $category->category_id = $cat;
                $category->save();
            }
        }
        if($request->tags){
            $deleteTags = MangaHasTag::where('manga_id', $id)->delete();
            $originalTags = explode(',', $request->tags);
            $tags = $this->removeSpecialAccents($request->tags);
            $tags = explode(',', $tags);
            for ($i=0; $i < count($tags); $i++) {
                $slug = $this->sanitizeText($tags[$i]);
                $slugExists = Tag::where('slug', $slug)->exists();
                if($slugExists){
                    $slugid = Tag::where('slug', $slug)->get()->first();
                    $hasTag = new MangaHasTag;
                    $hasTag->manga_id = $id;
                    $hasTag->tag_id = $slugid->id;
                    $hasTag->save();
                }else{
                    $createTag = new Tag;
                    $originalName = preg_replace('/[^A-Za-z0-9. -]/', ' ', $originalTags[$i]);
                    $createTag->name = trim($originalName);
                    $createTag->slug = strtolower($slug);
                    if($createTag->save()){
                        $hasTag = new MangaHasTag;
                        $hasTag->manga_id = $id;
                        $hasTag->tag_id = $createTag->id;
                        $hasTag->save();
                    }
                }
            }
        }

        if($manga->save()){
            Cache::forget('manga_shortcuts');
            Cache::forget('most_viewed');
            return redirect()->route('manga.edit', ['id' => $manga->id])->with('success', 'Manga actualizado correctamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $manga = Manga::where('id', $id)->get()->first();
        $path = "/manga/$manga->slug";
        $delete = Manga::destroy($id);
        if($delete){
            // Clear cache
            Cache::forget('home_slider');
            Cache::forget('most_viewed');
            Cache::forget('new_chapters_novel');
            Cache::forget('new_chapters_manga');
            Cache::forget('manga_shortcuts');
            Cache::forget('categories_home');
            Cache::forget('top_month');
            if(Storage::disk($this->disk)->exists($path)){
                Storage::disk($this->disk)->deleteDirectory($path);
            }
            return response()->json([
                'status' => "success",
                'msg' => "Eliminado correctamente"
            ]);
        }
        return response()->json([
            'status' => "error",
            'msg' => "Ups, algo paso",
        ]);
    }
}