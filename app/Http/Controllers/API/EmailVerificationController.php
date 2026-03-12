<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function sendEmailVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Email already verified.'
            ], 400);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'status' => 'Success',
            'message' => 'Verification link sent to your email.'
        ], 200);
    }


    public function verify(Request $request,$id, $hash){

      $user = User::findOr($id);
      if(!hash_equals((string) $hash, sha1($user->getEmailForVerification()))){
        return response()->json(['message' => 'Invalid verification link'], 403);

      }

        if($user->hasVerifiedEmail()){
            return response()->json(['message' => 'Email already verified']);
        }

        if($user->markEmailAsVerified()){
            event(new \Illuminate\Auth\Events\Verified($user));
        }
        return response()->json(['status' => 'Success', 'message' => 'Email verified successfully!']);
    }
}
