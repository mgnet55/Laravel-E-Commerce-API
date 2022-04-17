<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageManager
{

    public static function upload(Request &$request, string $formInputName, string $driver = 'public', string $fileName = null): ?string
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

    public static function update(Request &$request, string $formInputName, string $currentName, string $driver = 'public'): bool
    {
        if ($request->hasFile($formInputName)) {
            $request->file($formInputName)->storeAs('', name: $currentName, options: $driver);
            $request->offsetUnset($formInputName);
            return true;
        }
        return false;
    }


    public static function delete(string $imageName, string $driver = 'public'): bool
    {
        return Storage::disk($driver)->delete($imageName);
    }

}
