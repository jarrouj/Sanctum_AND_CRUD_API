<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
        ->allowedFilters([
            'name',

        ])
        ->allowedSorts([
            'id',

        ])
        ->paginate(3);

    return response()->json($products);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|required|image',
            'description' => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
            ]
        ]);

        $imageFile = $request->file('image');
        $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->storeAs('public/images', $filename);

        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'image' => $filename,
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id']
        ]);

        return response([
            'success' => true,
            'data' => $product,
        ]);
    }


    public function show(Product $product)
    {


        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response([
            'success' => true,
            'data' => $product,
        ]);
    }


    public function update(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'image' => 'nullable|image',
        'description' => 'required',
        'category_id' => [
            'required',
            Rule::exists('categories', 'id')
        ]
    ]);

    if ($request->hasFile('image')) {
        $imageFile = $request->file('image');
        $filename = uniqid() . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->storeAs('public/images', $filename);
        $validatedData['image'] = $filename;
    }

    $product->update($validatedData);

    return response([
        'success' => true,
        'data' => $product,
    ]);
}


    public function destroy(Product $product)
    {
        if (!$product) {
            return response([
                'success' => false,
            ], 404);
        }
        $product->delete();


        return response([
            'success' => true,
        ], 204);
    }
}
