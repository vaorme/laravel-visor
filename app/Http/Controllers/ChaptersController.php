<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChaptersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
        // return response()->json($request->all());
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
        $mangaSlug = Manga::firstWhere('id', $mangaid)->get();

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
                Storage::disk('public')->putFileAs("manga/".$mangaSlug[0]->slug."/".$request->slug, $file, $originalName);
                $images[] = "manga/".$mangaSlug[0]->slug."/".$request->slug."/".$originalName;
            }
        }

        $chapter = new Chapter;

        $chapter->order = $count;
        $chapter->name = $request->name;
        $chapter->slug = $request->slug;
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
            $response = [
                "status" => "success",
                "msg" => "Chapter $chapter->name created",
                "item" => $chapter
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
        $mangaSlug = Manga::firstWhere('id', $mangaid)->get();

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
                Storage::disk('public')->putFileAs("manga/".$mangaSlug[0]->slug."/".$request->slug, $file, $originalName);
                $images[] = "manga/".$mangaSlug[0]->slug."/".$request->slug."/".$originalName;
            }
        }

        $chapter = new Chapter;

        $chapter->order = $count;
        $chapter->name = $request->name;
        $chapter->slug = $request->slug;
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
            $response = [
                "status" => "success",
                "msg" => "Chapter $chapter->name created",
                "item" => $chapter
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
        if($chapter->images){
            $images = json_decode($chapter->images);
            foreach($images as $image){
                $folder = explode('/', $image);
                $removeFileName = array_pop($folder);
                $folder = implode('/', $folder);
                Storage::disk('public')->deleteDirectory($folder);
                //Storage::disk('public')->delete($image);
            }
        }

        $delete = Chapter::destroy($id);
        if(!$delete){
            return response()->json([
                'status' => "error",
                'msg' => "Ups, algo paso",
            ]);
        }

        return response()->json([
            'status' => "success",
            'msg' => "Eliminado correctamente",
            'id' => $id
        ]);
    }
}
