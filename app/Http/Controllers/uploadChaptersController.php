<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;

class uploadChaptersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $disk = 'public';

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
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        foreach($zipList as $zipFile){
            $pathInfo = pathinfo($zipFile);

            // Es novela (Simple o Multiple)
            if(isset($pathInfo['extension'])){
                if($pathInfo['extension'] == "txt"){
                    $isNovel = true;
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
            }
        }
        
        $tmp_path = "";
        $simpleChapterSlug = "";
        $simpleChapterName = "";

        $currentChapters = [];

        $currentDirectories = Storage::disk($this->disk)->allDirectories("manga/$manga->slug/");
        foreach($currentDirectories as $cDir){
            $currentChapters[] = basename($cDir);
        }

        if($isMultiple || $isNovel){
            $tmp_path = "tmp/$manga->slug/";
        }
        if($isSingle){
            $simpleName = basename($request->file('chapters')->getClientOriginalName());
            $simpleChapterName = trim(basename("$simpleName", ".zip").PHP_EOL);
            $slugChapterName = sanitize_title($simpleChapterName);
            $tmp_path = "tmp/$manga->slug/$slugChapterName";
            $simpleChapterSlug = $slugChapterName;
        }

        // Local
        $createTmpDirectory = Storage::makeDirectory($tmp_path);
        $storagePath = Storage::path($tmp_path);

        // Public
        Storage::disk($this->disk)->makeDirectory("manga/$manga->slug/$simpleChapterSlug");
        $publicStoragePath = Storage::disk($this->disk)->path("manga/$manga->slug/$simpleChapterSlug");

        $zip->extractTo($storagePath);
        $zip->close();

        if($isSingle){
            $chapterExists = Chapter::where('manga_id', $mangaid)->where('slug', $simpleChapterSlug)->exists();
            if(!in_array($simpleChapterSlug, $currentChapters) && !$chapterExists){
                $files = Storage::files($tmp_path);
                $collectImages = [];
                foreach($files as $file){
                    $baseFile = basename($file);
                    $collectImages[] = "manga/$manga->slug/$simpleChapterSlug/$baseFile";
                    // $fileRoute = $storagePath.$simpleChapterSlug."/".$baseFile;
                    $fileConten = Storage::disk('local')->get("tmp/$manga->slug/$simpleChapterSlug/$baseFile");
                    Storage::disk('public')->put("manga/$manga->slug/$simpleChapterSlug/$baseFile", $fileConten);
                }

                $res = $this->createChapter($mangaid, $simpleChapterName, $simpleChapterSlug, $collectImages);
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
                        
                        foreach($files as $file){
                            $baseFile = basename($file);
                            $collectImages[] = "manga/$manga->slug/$dirSlug/$baseFile";
                            // $fileRoute = $storagePath.$dirSlug."/".$baseFile;
                            $fileConten = Storage::disk('local')->get("tmp/$manga->slug/$dirSlug/$baseFile");
                            Storage::disk('public')->put("manga/$manga->slug/$dirSlug/$baseFile", $fileConten);
                        }
                        Storage::deleteDirectory("$tmp_path/$dirSlug");
                        
                        $res = $this->createChapter($mangaid, $dirName, $dirSlug, $collectImages);
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
                if(in_array($slugify, $arrayChapters) && !$chapterExists){
                    $response['excluded'][] = "$fileName fue omitido, ya existe el capitulo.";
                    continue;
                }

                $fileConten = Storage::disk('local')->get("tmp/$manga->slug/$nBaseName");
                $res = $this->createChapter($mangaid, $fileName, $slugify, "", $fileConten);

                if($res['status'] == "error"){
                    $response['error'] = [
                        'msg' => $res['msg']
                    ];
                }
                $response['created'][] = $res;
            }

            
        }
        Storage::deleteDirectory("tmp/$manga->slug/");

        return $response;
    }

    private function createChapter($mangaid, $chapterName, $chapterSlug, $chapterImages = "", $chapterContent = ""){
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
            if(!empty($chapterImages)){
                $create->images = json_encode($chapterImages);
            }
            if(!empty($chapterContent)){
                $create->content = $chapterContent;
            }
            $create->manga_id = $mangaid;
            
            $create->save();

            return [
                "status" => "success",
                "msg" => "Chapter $create->name created",
                "item" => $create
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
