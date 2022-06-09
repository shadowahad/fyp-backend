<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            "token" => $user->createToken('API_ACCESS_TOKEN')->plainTextToken,
            "user" => $user
        ];

        // 

        $request->authenticate();

        $request->session()->regenerate();

        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();

        // // Revoke all tokens...
        // $user->tokens()->delete();

        // // Revoke the token that was used to authenticate the current request...
        // $request->user()->currentAccessToken()->delete();

        // // Revoke a specific token...
        // $user->tokens()->where('id', $tokenId)->delete();

    }

    public function me(Request $request)
    {
        return Auth::guard('web')->user();
    }
}
