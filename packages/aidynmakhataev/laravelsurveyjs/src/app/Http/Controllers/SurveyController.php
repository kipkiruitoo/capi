<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Incomplete;
use App\Respondent;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use App\PhoneNumber;

class SurveyController extends Controller
{
    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */


    public function runSurvey($phone)
    {
        // dd($request);
        // dd($phone);
        $survey = Survey::where('id', 71)->first();
        $survey = $survey->toArray();

        $phonenum =  PhoneNumber::find($phone)->phone;
        // $incomplete  =  Incomplete::where('id', $request->respondent)->latest()->take(1)->get();
        // if (Incomplete::where('respondent', $request->respondent)->exists()) {
        //     # code...
        //     $incomplete = Incomplete::where('respondent', $request->respondent)->latest()->take(1)->get();
        //     $jsondata = $incomplete[0]->json;
        //     $jsondata = json_encode(json_decode($jsondata));
        //     // var_dump($jsondata);

        // } else {
        //     $jsondata = "no results";
        //     $jsondata = json_encode(json_decode($jsondata));
        // }

        // print_r($survey) ;
        // array_push($survey, ["respondent" => $request->respondent]);
        array_push($survey, Auth::user()->id);
        return view('survey-manager::survey', [
            'survey'    =>  $survey,
            'phonenumber' => $phone,
            // 'incomplete' => $jsondata,
            // 'respondent' => Respondent::where('id', $request->respondent)->get(),
            'selectedphone' => $phonenum,
            'agent' => Auth::user()->id,
            // 'callsession' => $request->callsession
        ]);
    }
}
