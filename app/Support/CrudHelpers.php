<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

trait CrudHelpers
{
    protected function storeUploadedFile(?UploadedFile $file, string $directory): ?string
    {
        if ($file === null) {
            return null;
        }

        return $file->store($directory, 'public');
    }
}
