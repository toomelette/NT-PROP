<?php


namespace App\Http\Requests\PR;


use Illuminate\Foundation\Http\FormRequest;

class PRFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'pap_code' => [
                'required',
                'string',
            ],
            'resp_center' => [
                'required',
                'string',
            ],
            'date' =>[
                'required',
                'date_format:Y-m-d',
            ],
            'items.*.description'=>[
                'required',
            ],
            'items.*.qty'=>[
                'required',
            ],
            'items.*.unit_cost'=>[
                'required',
            ],
            'items.*.item'=>[
                'required',
            ],
            'requested_by' => [
                'required',
            ]
        ];
    }
}