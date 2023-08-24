<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChaptersController extends Controller{

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $mangaid){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'images.*' => 'mimes:jpg,jpeg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }
        
        $response = [];
        $images = [];
        $count = "";
        
        // Get manga slug
        $mangaSlug = Manga::firstWhere('id', '=', $mangaid);

        $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $request->slug)->exists();
        if($chapterExists){
            $response = [
                "status" => "error",
                "msg" => "Ups, ya existe un capitulo con el slug $request->slug"
            ];
            return $response;
        }

        $orderChapters = Chapter::where('manga_id', $mangaid)->orderBy('id', 'DESC')->first();
        if($orderChapters){
            $count = $orderChapters->order + 1;
        }else{
            $count = 1;
        }

        // Uploading images
        if($request->has('images')){
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $files = $request->file('images');
            foreach($files as $file){
                $originalName = $file->getClientOriginalName();
                $extensionFile = $file->getClientOriginalExtension();
                $check = in_array($extensionFile, $allowedExtensions);
                if(!$check){
                    $response['excluded'][] = "$originalName fue excluido, solo puedes subir jpg, jpeg, png, gif";
                    continue;
                }
                // $images[] = $file->store("manga/".$mangaSlug[0]->slug."/".$request->slug, 'public');
                Storage::disk($request->disk)->putFileAs("manga/".$mangaSlug->slug."/".$request->slug, $file, $originalName);
                $images[] = "manga/".$mangaSlug->slug."/".$request->slug."/".$originalName;
            }
        }

        $chapter = new Chapter;

        $chapter->order = $count;
        $chapter->name = $request->name;
        $chapter->slug = $request->slug;
        $chapter->type = $request->chaptertype;
        $chapter->disk = $request->disk;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }
        if(!empty($images)){
            $chapter->images = json_encode($images);
        }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->manga_id = $mangaid;

        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => "success",
                "msg" => "Capítulo $chapter->name creado",
                "item" => $chapter,
                "manga_slug" => $mangaSlug->slug
            ];
            return $response;
        }
        $response = [
            "status" => "error",
            "msg" => "Ups, algo paso",
            "item" => $chapter
        ];
        return $response;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $chapter = Chapter::where('id', $id)->get()->first();
        if($chapter){
            return response()->json([
                'status' => "success",
                'chapter' => $chapter
            ]);
        }else{
            return response()->json([
                'status' => "error",
                'chapter' => $chapter
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }
        
        $chapter = Chapter::find($id);

        $response = [];

        $chapter->name = $request->name;
        $mangaSlug = Manga::where('id', $chapter->manga_id)->get()->first();
        if($chapter->slug != $request->slug){
            $slugExists = Chapter::where([
                ['manga_id', '=', $chapter->manga_id],
                ['slug', '=', $request->slug]
            ])->exists();
            //$manga = Manga::firstWhere('id', $chapter->manga_id)->get();
            if($slugExists){
                $response = [
                    "status" => "error",
                    "msg" => "Ups, ya existe un capitulo con el slug $request->slug"
                ];
                return $response;
            }

            $changeImagesSlug = str_replace($chapter->slug, $request->slug, $chapter->images);
            $chapter->images = $changeImagesSlug;
            
            Storage::disk($request->disk)->allFiles("/manga/$mangaSlug->slug/$chapter->slug");

            Storage::disk($request->disk)->move("/manga/$mangaSlug->slug/$chapter->slug", "manga/$mangaSlug->slug/$request->slug");

            $chapter->slug = $request->slug;
        }
        $chapter->type = $request->type;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->disk = $request->disk;

        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => "success",
                "msg" => "Capítulo $chapter->name actualizado",
                "item" => $chapter,
                "manga_slug" => $mangaSlug->slug
            ];
            return $response;
        }
        $response = [
            "status" => "error",
            "msg" => "Ups, algo paso",
            "item" => $chapter
        ];
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $chapter = Chapter::where('id', $id)->get()->first();
        $folder = 'manga/'.$chapter->manga->slug.'/'.$chapter->slug;
        if(Storage::disk($chapter->disk)->exists($folder)){
            Storage::disk($chapter->disk)->deleteDirectory($folder);
        }
        $delete = Chapter::destroy($id);
        if(!$delete){
            return response()->json([
                'status' => "error",
                'msg' => "Ups, algo paso",
            ]);
        }
        Cache::forget('new_chapters');
        return response()->json([
            'status' => "success",
            'msg' => "Eliminado correctamente",
            'id' => $id
        ]);
    }
    public function createChapter(Request $request, $mangaid){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'images.*' => 'mimes:jpg,jpeg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }
        
        $response = [];
        $images = [];
        $count = "";
        
        // Get manga slug
        $mangaSlug = Manga::firstWhere('id', '=', $mangaid);

        $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $request->slug)->exists();
        if($chapterExists){
            $response = [
                "status" => "error",
                "msg" => "Ups, ya existe un capitulo con el slug $request->slug"
            ];
            return $response;
        }

        $orderChapters = Chapter::where('manga_id', $mangaid)->orderBy('id', 'DESC')->first();
        if($orderChapters){
            $count = $orderChapters->order + 1;
        }else{
            $count = 1;
        }

        $chapter = new Chapter;

        $chapter->order = $count;
        $chapter->name = $request->name;
        $chapter->slug = $request->slug;
        $chapter->type = $request->chaptertype;
        $chapter->disk = $request->disk;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->manga_id = $mangaid;

        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => "success",
                "msg" => "Capítulo $chapter->name creado",
                "item" => $chapter,
                "manga_slug" => $mangaSlug->slug
            ];
            return $response;
        }
        $response = [
            "status" => "error",
            "msg" => "Ups, algo paso",
            "item" => $chapter
        ];
        return $response;
    }
}