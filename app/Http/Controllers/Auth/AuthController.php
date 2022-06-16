<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'number' => 'required|string|unique:users',
            'avatar' => 'string',
            'city' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($request->avatar) {
            $img = $request->get('avatar');
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $img = base64_decode($img);
            $imageName = date('mdYHis') . uniqid() . '.jpeg';
            Storage::disk('public')->put('users/' . $imageName, $img);
            $path = "users/" . $imageName;
            $request->merge(['avatar' => $path]);

            $request->avatar = $path;
        } else {
            $request->merge(['avatar' => 'users/default.jpeg']);
        }



        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->number = $request->number;
        $user->avatar = $request->avatar;
        $user->city = $request->city;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user_id = Auth::id();

        // dd($user_id);

        if ($request->avatar) {

            $request->validate([
                'name' => 'required|string',
                'number' => 'required|string',
                'avatar' => 'string',
                'city' => 'required|string',
            ]);
            $img = $request->get('avatar');
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $img = base64_decode($img);
            $imageName = date('mdYHis') . uniqid() . '.jpeg';
            Storage::disk('public')->put('users/' . $imageName, $img);
            $path = "users/" . $imageName;
            $request->merge(['avatar' => $path]);

            $request->avatar = $path;

            $user = User::find($user_id);

            $user->name = $request->name;
            $user->number = $request->number;
            $user->avatar = $request->avatar;
            $user->city = $request->city;

            $user->save();
        } else {
            $request->validate([
                'name' => 'required|string',
                'number' => 'required|string',
                'city' => 'required|string',
            ]);
            $user = User::find($user_id);
            $user->name = $request->name;
            $user->number = $request->number;
            $user->city = $request->city;

            $user->save();
        }

        return response()->json([
            'message' => 'Successfully updated user!'
        ], 201);
    }
}
