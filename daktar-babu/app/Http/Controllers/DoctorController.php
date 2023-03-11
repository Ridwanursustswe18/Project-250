<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    //
    public function index(Doctor $doctor)
    {
        try {
          
            $id = auth()->user()->id;
            if($id==$doctor->doc_id){
            $doctorProfile = DB::table('users')
                ->join('doctors', 'users.id', '=', 'doctors.doc_id')
                ->select('users.*', 'doctors.*')
                ->where('users.id', '=', $id)
                ->get();

            return $doctorProfile;
            }
            else{
                return response("you are not allowed",403);
            }
        } catch (Exception $e) {
            $e->getMessage();
        }

    }
    public function edit(Request $request, Doctor $doctor)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if ($user->id == $doctor->doc_id) {
            return response("you are allowed");
        } else {
            return response("you are not allowed", 403);
        }
    }
    public function update(Request $request, Doctor $doctor)
    {
        try {
            $id = auth()->user()->id;
           
            $user = User::find($id);
            if ($user->id == $doctor->doc_id) {
              
                $request->validate([
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,',
                    'experience' => 'nullable|string|max:255',
                    'bio_data' => 'nullable|string|max:255',
                    'category' => 'nullable|string|max:255',
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
                $doc = Doctor::where('doc_id', $id)
                    ->update([
                        'experience' => $request->experience,
                        'bio_data' => $request->bio_data,
                        'category' => $request->category,
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