<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Manga;
use App\Models\MangaBookStatus;
use App\Models\MangaDemography;
use App\Models\MangaHasCategory;
use App\Models\MangaHasTag;
use App\Models\MangaType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use ManipulateImage;

class ComicsController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }

    public function index(Request $request){
        $comics = Manga::latest();
        $param_search = strip_tags($request->s);
        $param_status = ($request->status)? strip_tags($request->status) : 'published';
        if(isset($param_search) && !empty($param_search)){
            $comics->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%');
            });
        }
        $comics->where('status', $param_status);
        $comics = $comics->paginate(20);
        $viewData = [
            'comics' => $comics
        ];
        if ($comics->lastPage() === 1 && $comics->currentPage() !== 1) {
            $queryParams = $request->query();
            $queryParams['page'] = 1;
            $redirectUrl = 'space/comics?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }

        return view('dashboard.comics.index', $viewData);
    }

    // * CREATE
    public function create(){
        $users = User::role(['moderador', 'administrador', 'developer'])->get();
        $types = MangaType::get();
        $comicStatus = MangaBookStatus::get();
        $demographies = MangaDemography::get();
        $categories = Category::get();
        $tags = Tag::get();
        $viewData = [
            'users' => $users,
            'tags' => $tags,
            'categories' => $categories,
            'types' => $types,
            'comicStatus' => $comicStatus,
            'demographies' => $demographies
        ];
        return view('dashboard.comics.form', $viewData);
    }
    public function store(Request $request){
        //return response()->json($request->all());
        $request->validate([
            'title' => ['required', 'max:240'],
            'slug' => ['required', 'max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'status' => ['required'],
            'comic_type' => ['required'],
            'comic_demography' => ['required']
        ]);

        $exists = Manga::orderBy('id', 'DESC');
        $count = "";
        if($exists->exists()){
            $order = $exists->first()->toArray();
            $count = $order['order'] + 1;
        }else{
            $count = 1;
        }
        $imageFile = $request->file('cover');
        $comic_slug = $request->slug;
        $fileName = "";
        if($imageFile){
            $fileName = $imageFile->hashName();
            Storage::disk($this->disk)->makeDirectory('covers/comic/'.$comic_slug.'/cover');
            
            $img = ManipulateImage::make($imageFile->path())->resize(458, null, function ($constraint) {
                $constraint->aspectRatio();
            })->fit(458, 646);
            $stored = Storage::disk($this->disk)->put('covers/comic/'.$comic_slug.'/cover/'.$fileName, $img->stream()->__toString());
        }

        // Fields
        $manga = new Manga;
        $manga->order = $count;
        if(!empty($imageFile)){
            $manga->featured_image = 'covers/comic/'.$comic_slug.'/cover/'.$fileName;
        }
        $manga->name = $request->title;
        $manga->alternative_name = $request->alternative_title;
        $manga->slug = $comic_slug;
        $manga->description = $request->description;
        $manga->status = $request->status;
        $manga->user_id = $request->author;
        $manga->type_id = $request->comic_type;
        $manga->book_status_id = $request->comic_status;
        $manga->demography_id = $request->comic_demography;
        $manga->release_date = $request->release_date;
        $manga->new_chapters_time = $request->next_chapter_when;
        $manga->new_chapters_date = $request->next_chapter_date;
        if($manga->save()){
            if(isset($request->categories)){
                foreach($request->categories as $cat){
                    $category = new MangaHasCategory();
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
            Cache::forget('new_records');
            Cache::forget('new_chapters');
            return redirect()->route('comics.edit', ['id' => $manga->id])->with('success', 'Manga creado correctamente');
        }
    }

    public function edit($id){
        $comic = Manga::find($id);
        if(!$comic){
            abort(404);
        }
        $users = User::role(['moderador', 'administrador', 'developer'])->get();
        $types = MangaType::get();
        $comicStatus = MangaBookStatus::get();
        $demographies = MangaDemography::get();
        $categories = Category::get();
        $chapters = Chapter::where('manga_id', $id)->orderBy('order', 'ASC')->get();
        $mangaHasCategories = MangaHasCategory::where('manga_id', '=', $id)->join('categories', 'categories.id', '=', 'manga_has_categories.category_id')
        ->get(['categories.id', 'categories.name']);
        $mangaHasTags = MangaHasTag::where('manga_id', '=', $id)->join('tags', 'tags.id', '=', 'manga_has_tags.tag_id')
        ->get(['tags.id', 'tags.name']);
        $viewData = [
            'comic' => $comic,
            'users' => $users,
            'categories' => $categories,
            'types' => $types,
            'comicStatus' => $comicStatus,
            'demographies' => $demographies,
            'has_categories' => $mangaHasCategories,
            'has_tags' => $mangaHasTags,
            'chapters' => $chapters
        ];

        return view('dashboard.comics.form', $viewData);
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => ['required', 'max:240'],
            'slug' => ['required', 'max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'status' => ['required'],
            'comic_type' => ['required'],
            'comic_demography' => ['required']
        ]);
        $manga = Manga::find($id);

        $imageFile = $request->file('cover');
        $comic_slug = $request->slug;
        if($imageFile){
            Storage::disk($this->disk)->deleteDirectory('covers/comic/'.$comic_slug.'/cover');
            Storage::disk($this->disk)->makeDirectory('covers/comic/'.$comic_slug.'/cover');

            $img = ManipulateImage::make($imageFile->path())->resize(458, null, function ($constraint) {
                $constraint->aspectRatio();
            })->fit(458, 646);
            $fileName = $imageFile->hashName();
            Storage::disk($this->disk)->put('covers/comic/'.$comic_slug.'/cover/'.$fileName, $img->stream()->__toString());

            $manga->featured_image = 'covers/comic/'.$comic_slug.'/cover/'.$fileName;
        }

        $manga->name = $request->title;
        $manga->alternative_name = $request->alternative_title;
        $manga->slug = $comic_slug;
        $manga->description = $request->description;
        $manga->status = $request->status;
        $manga->user_id = $request->author;
        $manga->type_id = $request->comic_type;
        $manga->book_status_id = $request->comic_status;
        $manga->release_date = $request->release_date;
        $manga->demography_id = $request->comic_demography;
        $manga->new_chapters_time = $request->next_chapter_when;
        $manga->new_chapters_date = $request->next_chapter_date;

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
            Cache::forget('new_records');
            Cache::forget('new_chapters');
            return redirect()->route('comics.edit', ['id' => $manga->id])->with('success', 'Comic actualizado correctamente');
        }
    }

    public function destroy($id){
        try {
            $manga = Manga::where('id', $id)->get()->first();
            $path = "/comic/$manga->slug";
            $delete = Manga::destroy($id);
            if($delete){
                if(Storage::disk($this->disk)->exists($path)){
                    Storage::disk($this->disk)->deleteDirectory($path);
                }
                Cache::forget('new_records');
                Cache::forget('new_chapters');
                return response()->json([
                    'status' => true,
                    'message' => "Eliminado correctamente"
                ], 200);
            }
        } catch (\Exception $e) {
            // Error response
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
