<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $request->validate([
            'name' => ['required', 'max:50'],
            'slug' => ['required', 'unique:categories', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);
        $response = [];
        $images = [];
        $count = "";
        
        // Get manga slug
        $mangaSlug = Manga::firstWhere('id', $mangaid)->get();

        $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $request->slug)->first();
        if($chapterExists){
            $response['error'] = "Ups, ya existe un capitulo con el slug $request->slug";
            return $response;
        }

        $orderChapters = Chapter::where('manga_id', $mangaid)->orderBy('id', 'DESC')->first();
        if($orderChapters){
            $count = $orderChapters->order + 1;
        }else{
            $count = 1;
        }

        // Uploading images
        if($request->hasFile('images')){
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
                Storage::disk('public')->putFileAs("manga/".$mangaSlug[0]->slug, $file, $originalName);
                $images[] = $originalName;
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
            $response['success'] = "Capitulo creado correctamente";
            return $response;
        }
        $response['error'] = $chapter;
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
