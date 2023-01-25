<?php


namespace App\Http\Requests\JR;


use Illuminate\Foundation\Http\FormRequest;

class JRFormRequest extends FormRequest
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
            'items.*.item'=>[
                'required',
            ],
            'items.*.qty'=>[
                'required',
                'string',
//                'min:1',
            ],
            'items.*.unit'=>[
                'required',
            ],
            'abc' => [
                'required',
            ],
            'requested_by' => [
                'required',
            ],
            'purpose' => [
                'required',
            ]
        ];
    }
}