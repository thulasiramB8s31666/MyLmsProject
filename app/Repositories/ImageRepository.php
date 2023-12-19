<?php

namespace App\Repositories;


use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageRepository implements BaseRepositoryInterface
{
    public function all()
    {
    }

    public function uploadImage($file, $directory, $filePath = null)
    {

        if ($filePath) {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $uniqueID = uniqid(); // Generate a unique identifier
        $fileName = hash('sha256', $uniqueID . Str::random(20)) . '.' . $file->getClientOriginalExtension();
        // Move the uploaded file to the specified directory
        $filePath = $file->move(public_path($directory), $fileName);
        $filePath = $directory . '/' . $fileName;
        return $filePath;
    }


    public function uploadVideo($file, $directory, $filePath = null)
    {
        try{
        if ($filePath) {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $uniqueID = uniqid();
        $fileName = hash('sha256', $uniqueID . Str::random(20)) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->move(public_path($directory), $fileName);
        $filePath = $directory . '/' . $fileName;

        return $filePath;
    }
    catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } 
    catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 422);
    }
    }


}

