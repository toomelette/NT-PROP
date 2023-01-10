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
            'items.*.item'=>[
                'required',
            ],
//            'items.*.qty'=>[
//                'required',
//            ],
//            'items.*.unit'=>[
//                'required',
//            ],
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