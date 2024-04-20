<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
            //'images.*' => 'mimes:jpg,jpeg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        
        $response = [];
        //$images = [];
        $count = "";
        
        // GET CURRENT COMIC
        $comic = Manga::firstWhere('id', '=', $mangaid);

        $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $request->slug)->exists();
        if($chapterExists){
            $response = [
                "status" => false,
                "message" => "Ups, ya existe un capitulo con el slug $request->slug"
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
        $chapter->type = $request->type;
        $chapter->disk = $request->disk;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }
        // if(!empty($images)){
        //     $chapter->images = json_encode($images);
        // }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->manga_id = $mangaid;
        //return response()->json($chapter);
        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => true,
                "message" => "Capítulo $chapter->name creado",
                "item" => $chapter,
                "manga_slug" => $comic->slug
            ];
            return $response;
        }
        $response = [
            "status" => false,
            "message" => "Ups, algo paso",
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
                'status' => true,
                'chapter' => $chapter
            ]);
        }else{
            return response()->json([
                'status' => false,
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $response = [];
        
        $chapter = Chapter::find($id);
        $chapter->name = $request->name;
        $comic = Manga::where('id', $chapter->manga_id)->get()->first();
        if($chapter->slug != $request->slug){
            $slugExists = Chapter::where([
                ['manga_id', '=', $chapter->manga_id],
                ['slug', '=', $request->slug]
            ])->exists();
            if($slugExists){
                $response = [
                    "status" => false,
                    "message" => "Ups, ya existe un capitulo con el slug $request->slug"
                ];
                return $response;
            }

            $changeImagesSlug = str_replace($chapter->slug, $request->slug, $chapter->images);
            $chapter->images = $changeImagesSlug;
            
            // GET ALL THE FILES OF THE PREVIOUS SLUG
            Storage::disk($request->disk)->allFiles("/comic/$comic->slug/$chapter->slug");
            // MOVE THE FILES TO THE NEW SLUG
            Storage::disk($request->disk)->move("/comic/$comic->slug/$chapter->slug", "comic/$comic->slug/$request->slug");

            // ASSIGN THE NEW SLUG
            $chapter->slug = $request->slug;
        }
        $chapter->type = $request->type;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }else{
            $chapter->price = "";
        }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->disk = $request->disk;

        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => true,
                "message" => "Capítulo $chapter->name actualizado",
                "item" => $chapter,
                "manga_slug" => $comic->slug
            ];
            return $response;
        }
        $response = [
            "status" => false,
            "message" => "Ups, algo paso",
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
        $folder = 'comic/'.$chapter->manga->slug.'/'.$chapter->slug;
        $folderExists = Storage::disk($chapter->disk)->exists($folder);
        if($folderExists){

            Storage::disk($chapter->disk)->deleteDirectory($folder);
        }
        $delete = Chapter::destroy($id);
        if(!$delete){
            return response()->json([
                'status' => false,
                'message' => "Ups, algo paso",
            ]);
        }
        Cache::forget('new_chapters');
        return response()->json([
            'status' => true,
            'message' => "Eliminado correctamente",
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
                'status' => false,
                'message' => $validator->errors()->all()
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
                "status" => false,
                "message" => "Ups, ya existe un capitulo con el slug $request->slug"
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
        $chapter->type = $request->type;
        $chapter->disk = $request->disk;
        if(!empty($request->price)){
            $chapter->price = $request->price;
        }else{
            $chapter->price = "";
        }
        if(!empty($request->content)){
            $chapter->content = $request->content;
        }
        $chapter->manga_id = $mangaid;

        if($chapter->save()){
            Cache::forget('new_chapters');
            $response = [
                "status" => true,
                "message" => "Capítulo $chapter->name creado",
                "item" => $chapter,
                "manga_slug" => $mangaSlug->slug
            ];
            return $response;
        }
        $response = [
            "status" => false,
            "message" => "Ups, algo paso",
            "item" => $chapter
        ];
        return $response;
    }

    // * REORDER CHAPTERS

    public function reorder(Request $request){
        try {
            $order = $request->input('order');
            foreach ($order as $key => $itemId) {
                Chapter::where('id', $itemId)->update(['order' => $key]);
            }
            Cache::forget('new_chapters');
            return response()->json([
                'status' => true,
                'message' => "Orden actualizado."
            ], 200);
        } catch (\Exception $e) {
            // Error response
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}