<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =  Cache::remember('products', 10, function () {
            return  QueryBuilder::for(Product::class)
        ->allowedFilters([
            'name',

        ])
        ->allowedSorts([
            'id',

        ])
        ->paginate(3);
    });

    return response([
        'success'=>true,
        'data'=>[
            $products,

        ]
    ]);

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

    Cache::put('products', $product);

    return response()->json([
        'success' => true,
        'data' => $product,

    ]);
}


public function show(Product $product)
{


    return response()->json([
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

    public function export()
    {
        $fileName = 'products.csv';

        return Excel::download(new ProductsExport(), $fileName);
        // return (new ProductsExport)->download($fileName);

    }
    public function import()
    {

            Excel::import(new ProductsImport, 'prod.xlsx');

            return redirect('/')->with('success', 'Import completed successfully.');

    }


}
