<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class EditHistoryController extends Controller
{
    public function index(Request $request){
        $activities = Activity::query()
            ->where('subject_type','=',$request->model)
            ->where('subject_id','=',$request->id)
            ->get();


        return view('ppu.activity_log.view')->with([
            'activities' => $activities,
        ]);
        return $request->model;
    }
}