<?php


namespace App\Http\Requests\PAR;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PARFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
           'article' => [
               'required',
               Rule::unique('inventory_ppe','article'),
           ],
        ];
    }
}