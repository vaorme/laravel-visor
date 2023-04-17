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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MangaController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $request->validate([
            'status' => ['max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        $status = ($request->status == null)? 'published': $request->status;
        $loop = Manga::where('status', '=', $status)
            ->join('users', 'manga.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.id')
            ->leftJoin('manga_type', 'manga.type_id', '=', 'manga_type.id')
            ->orderBy('id', 'desc')->get([
                'manga.id',
                'manga.name',
                'manga.alternative_name',
                'users.username',
                'profiles.avatar',
                'manga_type.name as type'
            ]);
        return view('admin.manga.index', ['loop' => $loop]);
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
            'name' => ['required', 'max:60'],
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
        if($imageFile){
            $featuredImage = Storage::disk('public')->putFile('manga/'.$mangaSlug.'/cover/', $request->file('featured_image'));
        }

        // Fields
        $manga = new Manga;
        $manga->order = $count;
        if(!empty($imageFile)){
            $manga->featured_image = $featuredImage;
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

        if($manga->save()){
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

            return redirect()->route('manga.edit', ['id' => $manga->id])->with('manga_success', 'Manga creado correctamente');
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
            'name' => ['required', 'max:60'],
            'slug' => ['required', 'max:60', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        $manga = Manga::find($id);

        $imageFile = $request->file('featured_image');
        $mangaSlug = $request->slug;
        if($imageFile){
            Storage::disk('public')->deleteDirectory('manga/'.$mangaSlug.'/cover/');
            $featuredImage = Storage::disk('public')->putFile('manga/'.$mangaSlug.'/cover/', $imageFile);
            $manga->featured_image = $featuredImage;
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
            return redirect()->route('manga.edit', ['id' => $manga->id])->with('manga_success', 'Manga actualizado correctamente');
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
            Storage::disk('public')->deleteDirectory($path);
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