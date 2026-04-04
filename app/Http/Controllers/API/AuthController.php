<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,avif,webp|max:2048'
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;

        try {
            $imagePath = null;
            if ($request->hasFile('profile_picture')):
                $imagePath = $request->file('profile_picture')->store('profile', 'public');
            endif;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile_picture' => $imagePath,
                'role' => 'author'
            ]);
            $user->sendEmailVerificationNotification();
            $token = $user->createToken('Blog_app_token')->plainTextToken;
            return response()->json([
                'status' => 'Success',
                'message' => 'Register Successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new UserResource($user),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Registration failed. Please try again later.' . $e
            ], 500);
        }
    }





    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            if (Auth::attempt($credentials)):
                $user = Auth::user();
                $user->tokens()->delete();
                $token = $user->createToken('Blog_app_token')->plainTextToken;
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Login Successfully',
                    'data' => [
                        'token' => $token,
                        'token_type' => 'Bearer',
                        'user' => new UserResource($user),
                    ],
                ], 200);
            endif;
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid Creadential'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }



    public function profile(Request $request)
    {
        $user = $request->user();
        $user->loadCount('blog_posts');
        if (!$user):
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found'
            ], 404);
        endif;
        return response()->json([
            'status' => 'Success',
            'data' => new UserResource($user)
        ], 200);
    }



    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user):
            return response()->json([
                'status' => 'Error',
                'message' => 'User not found'
            ], 404);
        endif;
        try {
            $user->tokens()->delete();
            return response()->json([
                'status' => 'Success',
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'bio' => 'nullable',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,avif,webp|max:2048'
        ]);
        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;

        $data = $request->only(['name', 'bio', 'profile_picture']);
        try {
            if ($request->hasFile('profile_picture')):
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $data['profile_picture'] = $request->file('profile_picture')->store('profile', 'public');
            endif;
            $data['name'] = $request->name;
            $data['bio'] = $request->bio;
            $user->update($data);
            return response()->json([
                'status' => 'Success',
                'message' => 'Profile updated successfully',
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }


    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ?
            response()->json([

                'status' => 'Success',
                'message' => 'Reset link sent to your email.'


            ], 200) :
            response()->json([
                'status' => 'Error',
                'message' => 'Unable to send reset link.'
            ], 500);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);
                    $user->setRememberToken(Str::random(60));
                    $user->save();

                    event(new PasswordReset($user));
                }
            );
            return
                $status === Password::PASSWORD_RESET ?
                response()->json(['status' => 'Success', 'message' => 'Password has been reset.'], 200)
                : response()->json(['status' => 'Error', 'message' => 'Invalid token or email.'], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => "Unable to reset the password."
            ], 500);
        }
    }



    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;
        try {
            $user = $request->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'The current password you entered is incorrect.'
                ], 401);
            }
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json([
                'status' => 'Success',
                'message' => 'Password updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => "Unable to change password"
            ], 500);
        }
    }

    public function getAuthorProfile(User $user)
    {     
        $user->loadCount('blog_posts');
        return response()->json([
            'status' => 'Success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'bio' => $user->bio,
                'profile_picture_url' => $user->profile_picture_url,
                'posts_count' => $user->blog_posts_count,
                'is_owner'=>auth('sanctum')?->id()=== $user->id,
                'joined_at' => $user->created_at->format('M Y'),

            ]
        ]);
    }
}
