<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait imageManager
{

    public function upload(Request &$request, string $formInputName, string $driver = 'public', string $fileName = null): ?string
    {
        if ($request->hasFile($formInputName)) {
            $fileName = $fileName ?? $formInputName . '_' . time();
            $fileName .= '.' . $request->file($formInputName)->extension();
            $request->file($formInputName)->storeAs('', name: $fileName, options: $driver);
            $request->offsetUnset($formInputName);
            return $fileName;
        }
        return null;
    }

    public function update(Request &$request, string $formInputName, string $currentName, string $driver = 'public'): ?string
    {
        if ($request->hasFile($formInputName)) {
            $request->file($formInputName)->storeAs('', name: $currentName, options: $driver);
            $request->offsetUnset($formInputName);
        }
        return true;
    }


    public function delete(string $imageName, string $driver = 'public')
    {
        return Storage::disk($driver)->delete($imageName);
    }

}
