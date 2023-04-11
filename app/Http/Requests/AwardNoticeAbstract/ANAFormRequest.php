<?php


namespace App\Http\Requests\AwardNoticeAbstract;


use Illuminate\Foundation\Http\FormRequest;

class ANAFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'ref_book' => [
                'required',
            ],
            'ref_number' => [
                'required',
            ],
        ];
    }
}