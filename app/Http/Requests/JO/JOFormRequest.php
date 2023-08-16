<?php


namespace App\Http\Requests\JO;


use Illuminate\Foundation\Http\FormRequest;

class JOFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'jo_number' => 'required',
            'date' => 'required',
        ];
    }
}