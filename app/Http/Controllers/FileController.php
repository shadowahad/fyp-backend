<?php

namespace App\Http\Controllers;

use App\Models\File as FileDb;

class FileController extends Controller
{
    public function index()
    {
        $files = FileDb::get();
        return $files;
    }
}
