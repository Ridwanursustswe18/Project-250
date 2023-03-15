<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function store(LoginRequest $request){
        $request->authenticate();

        $request->session()->regenerate();
        $id = auth()->user()->id;
        $user = User::find($id);
        
        $token = $user->createToken($request->email)->plainTextToken;
       
        return response()->json([
            'token'=>$token,
            'success' => 'User logged in successfully!',
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response('logged out successfully',200);
    }
}
