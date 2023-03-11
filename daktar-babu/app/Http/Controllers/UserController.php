<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(UserDetails $userdetail)
    {
        try {
          
            $id = auth()->user()->id;
           
            if($id==$userdetail->user_id){
                
             $userProfile = UserDetails::join('users', 'users.id', '=', 'user_details.user_id')
                    ->where('users.id', '=', $id)
                    ->get();


                return $userProfile;
            }
            else{
                return response("you are not allowed",403);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }

    }
    public function edit(Request $request, UserDetails $userDetail)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if ($user->id == $userDetail->user_id) {
            return response("you are allowed");
        } else {
            return response("you are not allowed", 403);
        }
    }
    public function update(Request $request, UserDetails $userDetail)
    {
        try {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->id == $userDetail->user_id) {

                $request->validate([
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',

                    'bio_data' => 'nullable|string|max:255',

                    'profile_photo_path' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
                ]);
                if ($request->hasFile('profile_photo_path')) {
                    $photo = $request->file('profile_photo_path');
                    $path = $photo->store('images', 'public');
                    $user->profile_photo_path = $path;
                    $user->save();
                }
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
                $doc = UserDetails::where('user_id', $id)
                    ->update([

                        'bio_data' => $request->bio_data,

                    ]);


                return response('User updated successfully');
            } else {
                return response('You are not allowed', 403);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }

    }
}