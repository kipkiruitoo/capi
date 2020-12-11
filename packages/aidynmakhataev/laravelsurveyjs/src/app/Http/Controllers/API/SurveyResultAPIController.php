<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Interview;
use App\Feedback;
use App\Incomplete;
use App\Respondent;

use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Support\Facades\DB;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResultResource;

class SurveyResultAPIController extends Controller
{
    public function index(Survey $survey)
    {
        $results = $survey->results()->paginate(config('survey-manager.pagination_perPage', 10));
        return SurveyResultResource::collection($results)
            ->additional(['meta' => [
                'survey'    =>  new SurveyResource($survey),
            ]]);
    }
    public function show(Survey $survey, $interview)
    {
        $inter = Interview::find($interview);
        $results = $survey->results()->where('interview', $interview)->get()->first();
        return array("result" => $results->json, "survey" => $survey);
    }
    /**
     * @param Survey $survey
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Survey $survey, Request $request)
    {
        $request->validate([
            'json'  =>  'required',
        ]);
        // echo $request->input('respondent');
        // Log::debug($request->input('respondent'));

        Log::info($request);
        $interview = new Interview;
        $agent =  $request->input('agent');
        // $phone = $request->phone;
        $respondent = $request->input('respondent');
        //  print_r($agent->agent);
        //  echo "<br><hr>";
        // $respondent = json_decode($respondent->respondent);
        //  print_r($respondent) ;
        // print_r($request->input('project'));
        $interview->agent = $agent;
        $interview->survey = $request->input('survey');
        $interview->phone = $request->input('phone');
        $interview->project = $request->input('project');
        // $interview->call_session = $request->input('callsession');
        // $interview->respondent = $respondent;
        $interview->date = $request->input('date');
        if ($interview->save()) {
            $result = $survey->results()->create([

                'json'          =>  $request->input('json'),
                'interview' => $interview->id,
                'ip_address'    =>  $request->ip(),
            ]);
            DB::table('incomplete')->where('respondent', '=', $respondent)->delete();
            DB::insert('insert into feedback (respondent_id, feedback, agent) values (?, ?, ?)', [$respondent, 'Successful', Auth::id()]);
            session()->flash("message", "Successfully Recruited");
            session()->flash('alert-type', "success");
            // dd($request->input('json'));
            $this->addrespondent($request->input('json'));
        } else {
            $result = "An error occured";
        }

        //  print($interview->id);

        return response()->json([
            'data'      =>  $result,
            'message'   =>  'Survey Result successfully created',
        ], 201);
    }


    public function addrespondent($respondent)
    {

        // dd($respondent);

        // dd($respondent["Q11"]);
        // DB::enableQueryLog();
        // $res->save()->tosql()

        $res = new Respondent;


        $res->name = $respondent["Q2"];
        $res->project = 75;
        $res->phone = $respondent["Q8"]["primary"];
        // 'phone1' => $respondent[5],


        $res->county = $respondent["Q10"];

        $res->education = $respondent["Q5"];
        $res->sex = $respondent["Q4"];
        $res->lsm = "Urban";

        $res->status = 'Active';
        $res->district = $respondent["Q11"]["District"];
        $res->division = $respondent["Q11"]["Division"];
        $res->location = $respondent["Q11"]["Location"];
        $res->sublocation = $respondent["Q11"]["Sub-location"];

        if ($res->save()) {
            $res->spedules()->create([
                'schedule' => $respondent["time"],
                'date' => $respondent["date"]
            ]);
        }

        // dd(DB::getQueryLog());
    }
}
