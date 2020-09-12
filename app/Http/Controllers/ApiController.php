<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApiController extends Controller
{
    public function projects()
    {
        $projects = DB::table('projects')->where('end_date', '>=', Carbon::today())->join('projects_agents', 'projects_agents.project_id', '=', 'projects.id')->where('projects_agents.user_id', Auth::user()->id)->get();

        return response()->json(["success" => true, 'projects' => $projects->toArray()]);
    }
}
