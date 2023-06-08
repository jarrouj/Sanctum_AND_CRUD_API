<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class apiController extends Controller
{
    public function index(Request $request){
      $users = new User();
      $users->name=$request->name;
      $users->email=$request->email;
      $users->password=$request->password;

      $users->save();
      return response()->json($users);

    }

    public function show(){
        $users=User::all();

        return response()->json($users);

    }
}
