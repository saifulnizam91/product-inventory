<?php

namespace App\Services;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

class ExcelParserService
{
    public function process(UploadedFile $file): int
    {
        // Validate file
        $this->validateFile($file);

        // Load spreadsheet
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Convert to array and process
        $rows = $worksheet->toArray();
        $header = array_map('strtolower', array_shift($rows));
        
        // Find column indexes
        $productIdIndex = array_search('product_id', $header);
        $statusIndex = array_search('status', $header);
        
        if ($productIdIndex === false || $statusIndex === false) {
            throw new \Exception("Excel file must contain 'product_id' and 'status' columns");
        }
        
        $updates = [];
        
        foreach ($rows as $row) {
            if (empty($row[$productIdIndex])) continue;
            
            $productId = (int)$row[$productIdIndex];
            $status = strtolower($row[$statusIndex] ?? '');
            
            if (!in_array($status, ['sold', 'buy'])) continue;
            
            $updates[$productId] = ($updates[$productId] ?? 0) + (($status === 'sold') ? -1 : 1);
        }
        
        // Apply updates in transaction
        return DB::transaction(function () use ($updates) {
            $affected = 0;
            foreach ($updates as $productId => $quantityChange) {
                $count = Product::where('product_id', $productId)
                    ->update([
                        'quantity' => DB::raw("GREATEST(0, quantity + {$quantityChange})") // Prevent negative quantities
                    ]);
                $affected += $count;
            }
            return $affected;
        });
    }

    protected function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception("Invalid file upload");
        }

        if ($file->getSize() > 5242880) { // 5MB
            throw new \Exception("File size exceeds 5MB limit");
        }

        $validTypes = ['xlsx', 'xls', 'csv'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $validTypes)) {
            throw new \Exception("Invalid file type. Only Excel files are accepted");
        }
    }
}