<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChapterUploadController extends Controller{
    private $extractDisk;

    public function __construct() {
        $this->extractDisk = 'local';
    }

    public function index(){
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
    public function subir(Request $request){
        $file = $request->file('archivo')->store('tmp');
        // $filename = hexdec(uniqid()).'.'.$file->extension();
        // $folder = uniqid().'-'. now()->timestamp;

        // $file->storeAs('tmp/'.$folder, $filename);

        return response()->json($file);
    }
    public function store(Request $request, $mangaid){
        $manga = Manga::where('id', $mangaid)->first();
        // 1. Subimos zip a ruta temporal
        // 2. creamos ruta y extraemos en ruta

        // $zip = new ZipArchive;
        // $zipOpen = $zip->open($request->file('chapters')->getRealPath());
        $zip = new \PhpZip\ZipFile();
        $zipOpen = $zip->openFile($request->file('chapters')->getRealPath());
        $zipList = $zip->getListFiles();

        if($zipOpen != TRUE){
            return $zipOpen;
        }

        $response = [];
        $isMultiple = false;
        $isSingle = false;
        $isNovel = false;
        $allowedExtensions = ['webp', 'jpg', 'jpeg', 'png', 'gif'];

        foreach($zipList as $zipFile){
            $pathInfo = pathinfo($zipFile);
            // Es novela (Simple o Multiple)
            if(isset($pathInfo['extension'])){
                if($pathInfo['extension'] == "txt"){
                    $isNovel = true;
                    break;
                }else{
                    $response['excluded'][] = $pathInfo['extension']." fue omitido, tipo de archivo invalido.";
                    break;
                }
            }

            // Es multiple
            if($zip->isDirectory($zipFile)){
                $isMultiple = true;
                break;
            }

            // Es simple y comprobamos que sean imagenes
            if(!$zip->isDirectory($zipFile) && in_array($pathInfo['extension'], $allowedExtensions)){
                $isSingle = true;
                break;
            }else{
                $response['excluded'][] = $pathInfo['extension']." fue omitido, tipo de archivo invalido.";
                break;
            }
        }

        // PATH FOR MULTIPLE & NOVEL
        $tmp_path = "tmp/$manga->slug/";

        $simpleChapterSlug = "";
        $simpleChapterName = "";

        $currentChapters = [];

        $currentDirectories = Storage::disk($request->disk)->allDirectories("comic/$manga->slug/");
        foreach($currentDirectories as $cDir){
            $currentChapters[] = basename($cDir);
        }
        if($isSingle){
            $simpleName = basename($request->file('chapters')->getClientOriginalName());
            $simpleChapterName = trim(basename("$simpleName", ".zip").PHP_EOL);
            $slugChapterName = sanitize_title($simpleChapterName);
            $tmp_path = "tmp/$manga->slug/$slugChapterName";
            $simpleChapterSlug = $slugChapterName;
        }
        // Local
        $createTmpDirectory = Storage::disk($this->extractDisk)->makeDirectory($tmp_path);
        $storagePath = Storage::disk($this->extractDisk)->path($tmp_path);

        // Public
        Storage::disk($request->disk)->makeDirectory("comic/$manga->slug/$simpleChapterSlug");
        $publicStoragePath = Storage::disk($request->disk)->path("comic/$manga->slug/$simpleChapterSlug");

        $zip->extractTo($storagePath);
        $zip->close();

        if($isSingle){
            $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $simpleChapterSlug)->exists();
            if(!in_array($simpleChapterSlug, $currentChapters) && !$chapterExists){
                $files = Storage::files($tmp_path);
                $collectImages = [];
                natcasesort($files);
                foreach($files as $file){
                    $baseFile = basename($file);
                    $collectImages[] = "comic/$manga->slug/$simpleChapterSlug/$baseFile";
                    // $fileRoute = $storagePath.$simpleChapterSlug."/".$baseFile;
                    $fileConten = Storage::disk($this->extractDisk)->get("tmp/$manga->slug/$simpleChapterSlug/$baseFile");
                    Storage::disk($request->disk)->put("comic/$manga->slug/$simpleChapterSlug/$baseFile", $fileConten);
                }

                $res = $this->createChapter($mangaid, $request->disk,$simpleChapterName, 'comic', $simpleChapterSlug, $collectImages);
                if($res['status'] == "error"){
                    $response['error'] = [
                        'msg' => $res['msg']
                    ];
                }

                $response['created'][] = $res;
            }else{
                $response['excluded'][] = "$simpleChapterName fue omitido, ya existe el capitulo.";
            }
        }
        if($isMultiple){
            $scandir = Storage::allDirectories($tmp_path);
            sort($scandir, SORT_NATURAL);
            foreach($scandir as $dir){
                if(basename($dir) === '__MACOSX' ){
                    continue;
                }

                $collectImages = [];
                $dirName = basename($dir);
                $dirSlug = sanitize_title($dirName);
                if(!FacadesFile::exists($storagePath.$dirSlug)){
                    rename($storagePath.$dirName, $storagePath.$dirSlug);
                }
                $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $dirSlug)->exists();
                if(!in_array($dirSlug, $currentChapters) && !$chapterExists){
                    if(is_dir($storagePath.$dirSlug)){
                        $files = Storage::files($tmp_path.$dirSlug);
                        natcasesort($files);
                        
                        foreach($files as $file){
                            $baseFile = basename($file);
                            $collectImages[] = "comic/$manga->slug/$dirSlug/$baseFile";
                            // $fileRoute = $storagePath.$dirSlug."/".$baseFile;
                            $fileConten = Storage::disk($this->extractDisk)->get("tmp/$manga->slug/$dirSlug/$baseFile");
                            Storage::disk($request->disk)->put("comic/$manga->slug/$dirSlug/$baseFile", $fileConten);
                        }
                        Storage::deleteDirectory("$tmp_path/$dirSlug");
                        
                        $res = $this->createChapter($mangaid, $request->disk, $dirName, 'comic', $dirSlug, $collectImages);
                        if($res['status'] == "error"){
                            $response['error'][] = [
                                'msg' => $res['msg']
                            ];
                            continue;
                        }
                        $response['created'][] = $res;
                    }
                }else{
                    $response['excluded'][] = "$dirName fue omitido, ya existe el capitulo.";
                }
            }
        }
        if($isNovel){
            $chaptersList = Chapter::where('manga_id', $mangaid)->get(['slug'])->toArray();
            $arrayChapters = [];
            foreach($chaptersList as $item){
                $arrayChapters[] = $item['slug'];
            }
            
            $scandir = Storage::allFiles($tmp_path);
            sort($scandir, SORT_NATURAL);
            foreach($scandir as $dirFile){
                $fileInfo = pathinfo($dirFile);
                $nBaseName = basename($dirFile);
                $fileName = $fileInfo['filename'];
                $fileExtension = $fileInfo['extension'];
                $slugify = sanitize_title($fileName);
                if($fileExtension != "txt"){
                    $response['excluded'][] = "$fileName fue omitido, tipo de archivo no permitido";
                    continue;
                }
                $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $slugify)->exists();
                if(in_array($slugify, $arrayChapters) && $chapterExists){
                    $response['excluded'][] = "$fileName fue omitido, ya existe el capitulo.";
                    continue;
                }

                $fileConten = Storage::disk('local')->get("tmp/$manga->slug/$nBaseName");
                $res = $this->createChapter($mangaid, $request->disk, $fileName, 'novel', $slugify, "", $fileConten);

                if($res['status'] == "error"){
                    $response['error'] = [
                        'msg' => $res['msg']
                    ];
                }
                $response['created'][] = $res;
            }

            
        }
        Cache::forget('new_chapters');
        Storage::deleteDirectory("tmp/$manga->slug/");

        return $response;
    }

    // public function subirImagenes(Request $request, $mangaid){
    //     $chapter = Chapter::find($request->chapterid);
    //     $manga = Manga::find($chapter->manga_id);

    //     $dbImages = json_decode($chapter->images);

    //     //Storage::disk($request->disk)->makeDirectory("manga/$manga->slug/$chapter->slug");
    //     foreach($request->images as $file){
    //         $originalName = $file->getClientOriginalName();
    //         $path = "comic/$manga->slug/$chapter->slug";
            
    //         // $storeFile = $file->store($path, $request->disk);
    //         if (Storage::disk($chapter->disk)->exists($path.'/'.$originalName)) {
    //             $storeFile = Storage::disk($chapter->disk)->putFileAs($path, $file, time().'-'.$originalName);
    //         }else{
    //             $storeFile = Storage::disk($chapter->disk)->putFileAs($path, $file, $originalName);
    //         }
    //         $newName = basename($storeFile);
    //         $dbImages[] = "comic/$manga->slug/$chapter->slug/$newName";
    //     }

    //     $chapter->images = json_encode($dbImages);

    //     if($chapter->save()){
    //         return response()->json([
    //             'status' => "success",
    //             'msg' => 'Subida completada',
    //             'data' => $dbImages
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status' => "error",
    //             'error' => "algo paso"
    //         ]);
    //     }
    // }


    public function updateImagesOrder(Request $request, $chapterid){
        $chapter = Chapter::find($chapterid);
        $old = $chapter->images;
        $chapter->images = json_encode($request->images);

        if($chapter->save()){
            return response()->json([
                'status' => "success",
                'msg' => "Orden actualizado",
                'old' => $old,
                'new' => $chapter->images
            ]);
        }else{
            return response()->json([
                'status' => "error",
                'error' => "algo paso"
            ]);
        }
    }
    
    public function deleteImage(Request $request, $chapterid){
        $chapter = Chapter::find($chapterid);
        $imagenes = json_decode($chapter->images);
        if(!is_null($imagenes[$request->index])){
            $exists = Storage::disk($chapter->disk)->exists($imagenes[$request->index]);
            if($exists){
                $eliminado = Storage::disk($chapter->disk)->delete($imagenes[$request->index]);
                if(!$eliminado){
                    return response()->json([
                        'status' => "error",
                        'msg' => "No se encontro archivo o no pudo ser eliminado",
                        'data' => $eliminado
                    ]);
                }
            }
        }
        array_splice($imagenes, $request->index, 1);
        $chapter->images = json_encode($imagenes);

        if($chapter->save()){
            return response()->json([
                'status' => "success",
                'msg' => "Imagen eliminada"
            ]);
        }else{
            return response()->json([
                'status' => "error",
                'error' => "Ups, algo paso"
            ]);
        }
    }

    private function createChapter($mangaid, $disk, $chapterName, $chapterType, $chapterSlug, $chapterImages = "", $chapterContent = ""){
        try{
            $create = new Chapter;
            $currentCount = Chapter::where('manga_id', $mangaid)->orderBy('id', 'DESC')->first();
            $count = "";
            
            if($currentCount){
                $order = $currentCount->toArray();
                $count = $order['order'] + 1;
            }else{
                $count = 1;
            }

            $create->order = $count;
            $create->name = $chapterName;
            $create->slug = $chapterSlug;
            $create->disk = $disk;
            $create->type = $chapterType;
            if(!empty($chapterImages)){
                $create->images = json_encode($chapterImages);
            }
            if(!empty($chapterContent)){
                $create->content = json_encode($chapterContent);
            }
            $create->manga_id = $mangaid;
            $mangaSlug = Manga::firstWhere('id', '=', $mangaid);
            
            $create->save();
            Cache::forget('new_chapters');
            return [
                "status" => "success",
                "msg" => "Chapter $create->name created",
                "item" => $create,
                "manga_slug" => $mangaSlug->slug
            ];
        }
            catch(\Exception $e){
            // do task when error
            return [
                "status" => "error",
                "msg" => $e->getMessage()
            ];
        }
    }
    public function uploadSingleImage(Request $request){
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpg,jpeg,png,gif'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }
        $chapter = Chapter::find($request->chapter_id);
        $comic = Manga::firstWhere('id', '=', $chapter->manga_id);

        $dbImages = json_decode($chapter->images);
        $excluded = [];

        if($request->has('image')){
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file = $request->file('image');

            // * GET ORIGINAL NAME WITHOUT EXTENSION
            $originalName = sanitize_title(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

            // * GET FILE EXTENSION IN LOWERCASE
            $extensionFile = strtolower($file->getClientOriginalExtension());

            // * SET STRUCTURE TO CHECK IF FILE EXISTS
            $originalStructure = "comic/".$comic->slug."/".$chapter->slug."/".$originalName.'.'.$extensionFile;
            if(Storage::disk($request->disk)->exists($originalStructure)){
                $originalName = $originalName.'-'.now()->format('YmdHis');
            }

            // * SET STRUCTURE OF FILE
            $fileStructure = "comic/".$comic->slug."/".$chapter->slug."/".$originalName.'.'.$extensionFile;
            $check = in_array($extensionFile, $allowedExtensions);
            if($check){
                Storage::disk($request->disk)->putFileAs("comic/".$comic->slug."/".$chapter->slug, $file, $originalName.'.'.$extensionFile);
                $dbImages[] = $fileStructure; 
            }else{
                $excluded = "$originalName fue excluido, solo puedes subir jpg, jpeg, png, gif";
            }
        }
        $chapter->images = json_encode($dbImages);

        if($chapter->save()){
            Cache::forget('new_chapters');
            return response()->json([
                "msg" => 'Imagen agregada',
                "status" => "success",
                "excluded" => $excluded,
                "data" => $dbImages
            ]);
        }
        return response()->json([
            "status" => "error",
            "msg" => "Ups, Algo paso"
        ]);
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