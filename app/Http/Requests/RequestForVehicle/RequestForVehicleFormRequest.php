<?php


namespace App\Http\Requests\RequestForVehicle;


use Illuminate\Foundation\Http\FormRequest;

class RequestForVehicleFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'rc' => 'required|string',
            'purpose' => 'required|string|max:512',
            'from' => 'required|date',
            'to' => 'nullable|date',
            'destination' => 'required|string|max:512',
            'passengers' => 'required|string',
            'requested_by' => 'required|string|max:255',
            'requested_by_position' => 'required|string|max:255',
//            'details.*.datetime_departure' => 'required|string',
//            'details.*.destination' => 'required|string',
        ];
    }
}