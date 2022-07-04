<?php

namespace App\Http\Controllers;

use App\Imports\AnalyticImport;
use App\Models\Analytic;
use App\Models\File as ModelsFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AnalyticController extends Controller
{
    //

    public function import(Request $request)
    {
        if ($request->input('attachment')) {
            try {
                $files = $request->attachment;
                $fileNameInReq = $request->name;
                // foreach ($files as $file) {
                $file_64 = $files[0]; //your base64 encoded data
                $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf
                // return response(['message' => $extension, 'code' => 200]);
                if ($extension === "vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    $extension = "xlsx";
                }
                $replace = substr($file_64, 0, strpos($file_64, ',') + 1);
                // find substring fro replace here eg: data:file/png;base64,
                $file = str_replace($replace, '', $file_64);
                $file = str_replace(' ', '+', $file);
                $fileName = Str::random(10) . '.' . $extension;
                $data = [
                    "name" => $fileNameInReq
                ];
                ModelsFile::create($data);
                Storage::disk('public')->put($fileName, base64_decode($file));
                // }
                $filelink = Storage::path("public/" . $fileName);
                Excel::import(new AnalyticImport, $filelink);
                return response(['message' => $filelink, 'code' => 200]);
            } catch (Throwable $e) {
                return $e;
            }
        }
    }

    public function getData(Request $request)
    {

        if ($request->value) {
            $titles = Analytic::whereIn('file_id', $request->value)->get();
            // dd($titles);
            // foreach ($titles as $title) {
            //     echo $title;
            // }
            return $titles;
        } else {
            return [];
        }
        // return $request;

    }
}
