<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\MangaBookStatus;
use App\Models\MangaDemography;
use App\Models\MangaType;
use App\Models\User;
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
        return view('admin.manga.create', [
            'users' => $users,
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
        $manga->status = $request->status;
        $manga->release_date = $request->release_date;
        $manga->user_id = $request->user_id;
        $manga->type_id = $request->type_id;
        $manga->book_status_id = $request->book_status_id;
        $manga->demography_id = $request->demography_id;
        
        if($manga->save()){
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
    public function edit($id)
    {
        $manga = Manga::find($id);
        $users = User::get();
        $mangaTypes = MangaType::get();
        $mangaBookStatus = MangaBookStatus::get();
        $mangaDemographies = MangaDemography::get();
        return view('admin.manga.edit', [
            'manga' => $manga,
            'users' => $users,
            'manga_types' => $mangaTypes,
            'manga_book_status' => $mangaBookStatus,
            'manga_demographies' => $mangaDemographies
        ]);
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
        return "desde update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delete = Manga::destroy($id);
        if($delete){
            $response['msg'] = "Manga eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}