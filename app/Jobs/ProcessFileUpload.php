<?php

namespace App\Jobs;

use App\Models\FileUpload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CsvFile;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath, $fileupload;
    public $timeout = 300;

    public function __construct($filePath, $fileupload)
    {
        $this->filePath = $filePath;
        $this->fileupload = $fileupload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batchSize = 1000;
        $data = [];
        $this->fileupload->update(['status' => 'processing']);

        // Read file
        $csv = Reader::createFromPath(Storage::path($this->filePath), 'r');
        $csv->setHeaderOffset(0);
        
        \Log::info("totalrow :". count($csv));

        try {
            foreach ($csv as $row) {
                // Clean non-UTF-8 characters
                $row = array_map(fn($value) => mb_convert_encoding($value, 'UTF-8', 'auto'), $row);
                $row = array_map(fn($value) => preg_replace('/[^\x20-\x7E]/', '', $value), $row);

                

                $data[] = [
                        'id' => $row['UNIQUE_KEY'],
                        'product_title' => $row['PRODUCT_TITLE'],
                        'product_description' => $row['PRODUCT_DESCRIPTION'],
                        'style' => $row['STYLE#'],
                        'sanmar_mainframe_color' => $row['SANMAR_MAINFRAME_COLOR'],
                        'size' => $row['SIZE'],
                        'color_name' => $row['COLOR_NAME'],
                        'piece_price' => $row['PIECE_PRICE'],
                        'created_at' => now(),
                        'updated_at' => now(),
                ];

                if (count($data) >= $batchSize) {
                    CsvFile::upsert($data, ['id'], ['size','piece_price', 'updated_at']);
                    $data = []; // Reset batch
                }
            }

            if (!empty($data)) {
                CsvFile::upsert($data, ['id'],['size','piece_price','updated_at']);
            }

            $this->fileupload->update(['status' => 'completed']);
            // Remove temporary file after processing
            Storage::delete($this->filePath);
        }
        catch (\Throwable $e) {
            // Log the error
            \Log::error('File processing failed', [
                'file_id' => $this->fileupload->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Mark status as failed
            $this->fileupload->update(['status' => 'failed']);
        }
    }
}
