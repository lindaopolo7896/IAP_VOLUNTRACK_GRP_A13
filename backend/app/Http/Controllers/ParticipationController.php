<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Databases
use App\Models\User;
use App\Models\Participation;
use App\Models\Volunteer;
use App\Models\Opportunity;
use App\Models\OtpCode;

//Resources
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\OtpEmail;
use App\Mail\PasswordResetEmail;
use App\Services\MembershipService;
use Carbon\Carbon;

class ParticipationController extends Controller
{
    public function list(){
        $participations = Participation::all();
        return response()->json($participations, 200);
    }

    public function getParticipation($id){
    $participation = Participation::find($id);
    if(!$participation){
        return response()->json(['message' => 'Participation not found'], 404);
    }
    return response()->json($participation, 200);
    }

    public function myParticipations(Request $request){
        $user = $request->user();
        $volunteer = MembershipService::getMembership($user);
        $participations = Participation::where('volunteer_id', $volunteer->id)->get();
        $totalHours = $participations->sum('total_hours');
        return response()->json([
            'participations' => $participations,
            'total_hours' => $totalHours
        ], 200);

    }

    public function oppParticipations($opportunity_id){
        $participations = Participation::where('opportunity_id', $opportunity_id)->get();
        return response()->json($participations, 200);
    }

    public function create(Request $request){
        $data = $request->validate([
        'volunteer_id' => 'required|string',
        'opportunity_id' => 'required|exists:opportunities,id',
        'check_in' => 'nullable|date',
        'check_out' => 'nullable|date|after:check_in',
        ]);

        if (!empty($data['check_in']) && !empty($data['check_out'])) {
            $ci = Carbon::parse($data['check_in']);
            $co = Carbon::parse($data['check_out']);

            // precise fractional hours, rounded to 2 decimals
            $data['total_hours'] = round($co->diffInSeconds($ci) / 3600, 2);
        } else {
            $data['total_hours'] = null;
        }
    }

    public function delete($id){
        $participation = Participation::find($id);
        if(!$participation){
            return response()->json(['message' => 'Participation not found'], 404);
        }
        $participation->delete();
        return response()->json(['message' => 'Participation deleted'], 200);
    }

}
