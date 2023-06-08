<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user= User::where('email',$request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The credentials you entered are incorrect.']
            ]);
        }

        return response()->json([
            'user'=>$user,
            'token'=>$user->createToken('laravel_api_token')->plainTextToken
          ]);
        // else{
        //     return response(['success'=>true,'data'=>[
        //         'user'=>$request->name,
        //         'email'=>$request->email,
        //         'email_verified_at'=>$request->email_verified_at,
        //         'updated_at'=>$request->updated_at,
        //         'created_at'=>$request->created_at,
        //         'id'=>$request->id
        //     ]]);
        // }

    }
}
