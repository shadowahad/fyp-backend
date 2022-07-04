<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\userRegisterMail;

class UserController extends Controller
{
    //
    public function index(){
        $data = [
            'data' => User::all()
        ];
        return response()->json($data, 200);
    }

    public function delete($id){
        $user =  User::find($id);
        $user = $user->delete();
        return "User deleted Successfully";
    }

    public function show($id){
        $data = [
            'data' => User::find($id)
        ];
        return response()->json($data, 200);
    }

    public function update($id, Request $request){
        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = 'Agent';
        if(isset($request->password)){
            $user->password = Hash::make($password);
        }
        $user->save();

        return "User updated Successfully";
    }
}
