<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\UserDetails;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', Rules\Password::defaults()],
            ]);
            $user = User::create([
                'name' => $request->name,
                'type' => $request->type,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($request->type == 'doctor') {
                $doctorInfo = Doctor::create([
                    'doc_id' => $user->id,
                    'status' => 'active'
                ]);
            } else if ($request->type == 'user') {
                $userInfo = UserDetails::create([
                    'user_id' => $user->id,
                    'status' => 'active'
                ]);
            }
            event(new Registered($user));


            return response($user);

        } catch (Exception $e) {
            echo ($e->getMessage());
        }

    }
}