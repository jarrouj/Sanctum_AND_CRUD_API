<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Illuminate\Validation\Rule;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $users = QueryBuilder::for(User::class)
    //     ->allowedFilters([
    //         AllowedFilter::exact('name'),
    //     ])
    //     ->paginate(10);
    //      return response()->json($users);

    // }


    public function index()
    {
        $users = QueryBuilder::for(User::class)
        ->allowedFilters([
            AllowedFilter::exact('name'),
            AllowedFilter::exact('email'),
            // Add more filters as needed
        ])
        ->allowedSorts([
            AllowedSort::field('id'),

            // Add more sorts as needed
        ])
        ->paginate(3);

    return response()->json($users);
    }



    public function store(Request $request)
{
    $user = auth()->user();

    $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => [
            'required',
            Rule::unique('users')->ignore($user->id),
        ],
        'password' => 'required|min:8',
        'role' => 'required|exists:roles,name'
    ]);

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
    ]);






        $user->assignRole($validatedData['role']);



        $roles = $user->getRoleNames();


    return response()->json([
        'success' => true,
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $roles,



        ],
    ]);
}






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

            ],
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {


        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,',
            'password' => 'required|min:8',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);

        $user->save();

        return response([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,


            ],
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {


    if (!$user) {
        return response([
            'success'=>false,
        ],404);
    }
$user->delete();


    return response([
        'success'=>true,
    ],204);
    }



    public function assignRoleToUser()
    {
        $user = User::where('name', 'Admin')->first();
        $role = Role::where('name', 'admin')->first();

        $user->assignRole($role);

    }
}
