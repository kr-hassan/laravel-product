<?php

namespace App\Http\Controllers;
use App\Events\ProductUpdated;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

//use ProductUpdated;

class ProductController extends Controller
{
    public function fetchProducts()
    {
        $response = Http::get('https://fakestoreapi.com/products');


        if ($response->successful()) {
            $products = $response->json();

            foreach ($products as $productData) {
                $product = Product::updateOrCreate(
                    ['id' => $productData['id']],
                    [
                        'name' => $productData['title'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                    ]
                );

                event(new ProductUpdated($product));
            }

            $allProducts = Product::all();
            return view('products.index', compact('allProducts'));
        }

        return response()->json(['message' => 'Failed to fetch products from API.'], 500);
    }
}
