<?php

namespace App\Http\Controllers;

use App\Models\Appoinments;
use App\Models\User;
use Illuminate\Http\Request;

class AppoinmentController extends Controller
{
    //
    public function index()
    {
        //retrieve all appointments from the user
        $appointment = Appoinments::where('user_id', auth()->user()->id)->get();
        $doctor = User::where('type', 'doctor')->get();

        //sorting appointment and doctor details
        //and get all related appointment
        foreach ($appointment as $data) {
            foreach ($doctor as $info) {
                $details = $info->doctor;
                if ($data['doc_id'] == $info['id']) {
                    $data['doctor_name'] = $info['name'];
                    $data['doctor_profile'] = $info['profile_photo_path']; 
                    $data['category'] = $details['category'];
                }
            }
        }

        return $appointment;
    }

    public function store(Request $request)
    {
        //this controller is to store booking details post from mobile app
        
        $appointment = new Appoinments();
        $appointment->user_id = auth()->user()->id;
        $appointment->doc_id = $request->get('doctor_id');
        $appointment->date = $request->get('date');
        $appointment->day = $request->get('day');
        $appointment->time = $request->get('time');
        $appointment->status = 'upcoming'; //new appointment will be saved as 'upcoming' by default
        $appointment->save();

        //if successfully, return status code 200
        return response()->json([
            'success' => 'New Appointment has been made successfully!',
        ], 200);

    }

}
