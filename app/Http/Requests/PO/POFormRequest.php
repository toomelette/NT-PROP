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
            'po_number' => 'required',
            'date' => 'required',
        ];
    }
}