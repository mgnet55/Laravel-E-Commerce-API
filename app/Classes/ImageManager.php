<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageManager
{
    // return file name with extension
    public static function generateName(Request &$request, string $formInputName, string $prefix = null): ?string
    {
        if ($request->hasFile($formInputName)) {
            $fileName = $prefix ?? $formInputName;
            $fileName .= '_' . time().'.' . $request->file($formInputName)->extension();
            return $fileName;
        }
        return null;
    }

    public static function upload(Request &$request, string $formInputName, string $driver = 'public', string $fileName = null): ?string
    {
        if ($request->hasFile($formInputName)) {
            $fileName = $fileName ?? self::generateName($request, $formInputName);
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
            $request->request->remove($formInputName);
            return true;
        }
        return false;
    }


    public static function delete(string $imageName, string $driver = 'public'): bool
    {
        return Storage::disk($driver)->delete($imageName);
    }

}
