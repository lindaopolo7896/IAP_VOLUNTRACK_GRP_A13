<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Databases
use App\Models\User;
use App\Models\Organisation;
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


class OpportunityController extends Controller
{

    public function list(Request $request){
        $opportunities = Opportunity::all();
        return response()->json($opportunities, 200);
    }

    public function get(Request $request, $id){
        $opportunity = Opportunity::find($id);
        if(!$opportunity){
            return response()->json(['message' => 'Opportunity not found'], 404);
        }
        return response()->json($opportunity, 200);
    }

    public function create(Request $request){
        $user = $request->user();
        $organisation = MembershipService::getMembership($user);
        if(!$user->role === 'organisation' || !$organisation){
            return response()->json(['message' => 'Only organisations can create opportunities'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'required_skills' => 'required|string',
            'num_volunteers_needed' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'schedule' => 'required|string|max:255',
            'benefits' => 'nullable|string|max:255',
            'application_deadline' => 'required|date|before_or_equal:start_date',
            'location' => 'required|string|max:255',
        ]);
        $data ['organisation_id'] = $organisation->id;

        $opportunity = Opportunity::create($data);
        return response()->json(['message'=>'Opportunity Created Successfuly','opportunity'=>$opportunity], 201);
    }

    public function update(Request $request, $id){
        $user = $request->user();
        $organisation = MembershipService::getMembership($user);
        if(!$user->role === 'organisation' || !$organisation){
            return response()->json(['message' => 'Only organisations can update opportunities'], 403);
        }

        $opportunity = Opportunity::find($id);
        if(!$opportunity || $opportunity->organisation_id !== $organisation->id){
            return response()->json(['message' => 'Opportunity not found or access denied'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'required_skills' => 'sometimes|required|string',
            'num_volunteers_needed' => 'sometimes|required|integer|min:1',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'schedule' => 'sometimes|required|string|max:255',
            'benefits' => 'nullable|string|max:255',
            'application_deadline' => 'sometimes|required|date|before_or_equal:start_date',
            'location' => 'sometimes|required|string|max:255',
        ]);

        $opportunity->update($data);
        return response()->json(['message'=>'Opportunity Updated Successfuly','opportunity'=>$opportunity], 200);
    }

    public function delete(Request $request, $id){
        $user = $request->user();
        $organisation = MembershipService::getMembership($user);
        if(!$user->role === 'organisation' || !$organisation){
            return response()->json(['message' => 'Only organisations can delete opportunities'], 403);
        }

        $opportunity = Opportunity::find($id);
        if(!$opportunity || $opportunity->organisation_id !== $organisation->id){
            return response()->json(['message' => 'Opportunity not found or access denied'], 404);
        }

        $opportunity->delete();
        return response()->json(['message'=>'Opportunity Deleted Successfuly'], 200);
    }


}
