<?php


namespace App\Http\Requests\InventoryPPE;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryPPEFormRequest extends FormRequest
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