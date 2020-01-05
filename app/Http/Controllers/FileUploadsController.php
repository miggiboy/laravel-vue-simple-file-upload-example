<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileUploadsController extends Controller
{
    public function store()
    {
        $files = collect(request()->file('files'))->map(function ($file) {
            return [
                'name' => $file->getClientOriginalName(),
                'path' => $file->store('temporary-file-uploads', 'local')
            ];
        });

        return [
            'files' => $files
        ];
    }

    public function destroy()
    {
        collect(request()->input('files'))->each(function ($file) {
            Storage::disk('local')->delete($file);
        });
    }
}
