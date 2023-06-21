<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Laravel\Pennant\Feature;

class CategoryController extends Controller
{
    public function index()
{
    $categories = QueryBuilder::for(Category::class)
        ->allowedFilters([
            'name',
        ])
        ->where('is_active', true)
        ->allowedSorts([
            'id',
        ])
        ->with('products')
        ->paginate(3);

    return response()->json($categories);
}

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:20',
        'is_active' => 'required|nullable|boolean',
    ]);

    $category = Category::create([
        'name' => $validatedData['name'],
        'is_active' => $validatedData['is_active'] ?? true,
    ]);

    return response()->json([
        'success' => true,
        'data' => $category,
    ]);
}



    public function show(Category $category)
{
    return response([
        'success' => true,
        'data' => $category,
    ]);
}


public function update(Request $request, Category $category)
{

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'is_active' => 'required|boolean',
    ]);


    $category->name = $validatedData['name'];
    $category->is_active = $validatedData['is_active'];
    $category->save();

    return response([
        'success' => true,
        'data' => $category,
    ]);
}

    public function destroy(Category $category)
    {


        if (!$category) {
            return response([
                'success' => false,
            ], 404);
        }
        $category->delete();


        return response([
            'success' => true,
        ], 204);
    }
}
