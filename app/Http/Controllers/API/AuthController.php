<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator =Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:8|confirmed',
            'profile_picture'=>'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if($validator->fails()):
            return response()->json([
                'status'=>'Error',
                'message'=> $validator->errors()
            ], 422);
        endif;

        $imagePath =null;
        if($request->hasFile('profile_picture')):
            $path = $request->file('profile_picture')->store('profile','public');
            $imagePath = $path;

        endif;

       
        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'profile_picture'=> $imagePath,
            'role'=> $request->role
        ]);

        return response()->json([
            'status'=>'Success',
            'message'=>'Register Successfully'
        ],201);
    }





public function login(Request $request){
    $credentials  = $request->validate([
   'email'=>'required|email',
   'password'=>'required'
    ]);
if(Auth::attempt($credentials)):
    $user = Auth::user();
    $token = $user->createToken('Blog_app_token')->plainTextToken;

    return response()->json([
        'status'=>'Success',
        'message'=>'Login Successfully',
        'data'=> [
            'token'=>$token,
            'user'=>$user
        ]
    ],200);
endif;

  return response()->json([
    'status'=>'Error',
    'message'=>'Invalid Creadential'
  ],401);
}



public function profile(Request $request){
$user =$request->user();

 return response()->json([
    'status'=> 'Success',
     'data'=> $user
 ],200);

}
public function logout(Request $request){

$request->user()->tokens()->delete();

return response()->json([
        'status' => 'Success',
        'message' => 'Logged out successfully'
    ], 200);
}
}
