<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;


class StockController extends Controller
{
    //


    public function create()
    {
        $products = Product::all();
        $stocks = Stock::with('product')->get();
        return view('product.index', compact('products','stocks'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'stocks.*.product_id' => 'required|exists:products,id',
            'stocks.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            foreach ($data['stocks'] as $stockData) {
                Stock::create($stockData);
            }
        } catch (\Exception $e) {
            // Log or handle the exception
        }

        return redirect()->route('product.index')->with('success', 'Stocks added successfully.');
    }

    public function getStock(Request $request)
        {
            $productId = $request->input('product_id');
            $previousStock = Stock::where('product_id', $productId)->latest()->value('quantity');

            return response()->json(['stock' => $previousStock]);
        }

}
