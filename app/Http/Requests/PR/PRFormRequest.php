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
            'prDate' => 'required|date_format:Y-m-d',
            'papCode' => [
                'required',
                'string',
            ],
            'respCenter' => [
                'required',
                'string',
            ],
            'items.*.description'=>[
                'required',
            ],
            'items.*.qty'=>[
                'required',
            ],
            'items.*.unitCost'=>[
                'required',
            ],
            'items.*.item'=>[
                'required',
            ]
        ];
    }
}