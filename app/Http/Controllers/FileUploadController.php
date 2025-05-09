<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileUpload;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function index()
    {
        
        $lists = FileUpload::all();

        return view('index', compact('lists'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt'
        ]);

        if ($validator->fails()) {
            return redirect()->route('index')->withErrors($validator);
        }

        // Store file temporarily
        $path = $request->file('file')->store('uploads');

        Log::info("Original name : ".$request->file('file')->getClientOriginalName());

        $fileupload = FileUpload::create([
            'file_name' => $request->file('file')->getClientOriginalName(),
            'status' => 'pending'
        ]);
        // Log::info($path);

        // Dispatch job to process the file asynchronously
        ProcessFileUpload::dispatch($path,$fileupload);

        return redirect()->route('index')->with('success', 'File uploaded successfully, processing started.');
    }

    public function update()
    {
        $lists = FileUpload::all();
        return view('updates', compact('lists'));
    }
}
