<?php


namespace App\Http\Controllers;


use Illuminate\Support\Str;

class AjaxController extends Controller
{
    public function get($for){
        if($for == 'educational_background'){
            return view('ajax.employee.add_school');
        }

        if($for == 'eligibility'){
            return view('ajax.employee.add_eligibility');
        }

        if($for == 'work_experience'){
            $rand = Str::random(16);
            return [
                'view' => view('ajax.employee.add_work_experience')->with([
                                'rand' => $rand,
                            ])->render(),
                'rand' => $rand,
            ];
        }

        if($for == 'add_ppmp_row'){
            return view('ajax.ppmp.add_row');
        }
    }
}