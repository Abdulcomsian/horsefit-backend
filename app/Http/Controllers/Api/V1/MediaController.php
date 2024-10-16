<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_input' => 'required|file',
            // $file_input => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }
        $folder_name = $request->input('folder_name', 'posts');
        $file = $request->file('file_input');
        $path = $file->store($folder_name, env('FILESYSTEM_DRIVER', 'public'));
        $fullUrl = Storage::disk(env('FILESYSTEM_DRIVER'))->url($path);
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'File uploaded successfully.',
            'full_url' => $fullUrl,
        ], 200);
    }

    public function deleteFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_url' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => null,
            ], 422);
        }
        $fileUrl = $request->input('file_url');

        $disk = env('FILESYSTEM_DRIVER', 'public');
        $baseUrl = Storage::disk($disk)->url('');
        $relativeFilePath = str_replace($baseUrl, '', $fileUrl);
        // \Log::info([
        //     'fileUrl' => $fileUrl,
        //     'baseUrl' => $baseUrl,
        //     'relativeFilePath' => $relativeFilePath,
        // ]);
        if (Storage::disk($disk)->exists($relativeFilePath)) {
            Storage::disk($disk)->delete($relativeFilePath);
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'File deleted successfully.',
                'data' => null
            ], 200);
        }

        return response()->json([
            'status' => true,
            'code' => 404,
            'message' => 'File not found.',
            'data' => null
        ], 404);
    }
}
