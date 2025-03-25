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

        // Check if the request was successful
        if ($response->successful()) {
            $products = $response->json(); // Get the products as an array

            // Loop through the products and store them in the database
            foreach ($products as $productData) {
                // Store or update the product
                $product = Product::updateOrCreate(
                    ['id' => $productData['id']],
                    [
                        'name' => $productData['title'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                    ]
                );

                // Broadcast the ProductUpdated event to notify clients
                event(new ProductUpdated($product));
            }

            $allProducts = Product::all(); // Fetch all products from the database
            return view('products.index', compact('allProducts'));
        }

        return response()->json(['message' => 'Failed to fetch products from API.'], 500);
    }
}
