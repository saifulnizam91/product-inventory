<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ExcelParserService;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function updateInventory(Request $request, ExcelParserService $excelParser)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120' // 5MB
        ]);

        try {
            $processedCount = $excelParser->process($request->file('file'));
            
            return response()->json([
                'success' => true,
                'message' => "Updated {$processedCount} product quantities",
                'data' => Product::all()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
