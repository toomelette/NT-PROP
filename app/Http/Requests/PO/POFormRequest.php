<?php


namespace App\Http\Requests\PO;


use Illuminate\Foundation\Http\FormRequest;

class POFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'delivery_date' => 'required|date_format:Y-m-d',
        ];
    }
}