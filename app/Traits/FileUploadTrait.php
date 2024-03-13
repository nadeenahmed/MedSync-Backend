<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function handleFileUpload($request, $fileKey, $uploadDirectory, $uploadDirectoryPath)
    {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $uniqueFileName = Str::uuid() . '_' . $file->getClientOriginalName();
            $uploadDirectory = $uploadDirectory;
            $file->storeAs($uploadDirectory, $uniqueFileName);
            $relativePath = $uploadDirectoryPath;
            $filePath = $relativePath . $uniqueFileName;
            return url($filePath);
        } else {
            return null;
        }
    }
}
