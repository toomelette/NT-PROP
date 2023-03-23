<?php


namespace App\Http\Requests\Supplier;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
           'name' => [
               'required',
           ],
        ];
    }
}