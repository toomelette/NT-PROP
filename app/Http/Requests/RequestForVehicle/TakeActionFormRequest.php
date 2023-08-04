<?php


namespace App\Http\Requests\RequestForVehicle;


use Illuminate\Foundation\Http\FormRequest;

class TakeActionFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'details.*.vehicle_assigned' => 'required_if:action_made,APPROVED|string',
            'details.*.driver_assigned' => 'required_if:action_made,APPROVED|string',
            'reason' => 'required_if:action_made,DISAPPROVED',
        ];
    }

    public function messages()
    {
        return [
            'details.*.vehicle_assigned' => [
                'required_if' => 'Field is required',
            ],
        ];
    }
}