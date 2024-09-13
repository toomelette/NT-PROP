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
            'dateacquired' => 'required',
            'serial_no' => 'required',
            'article' => 'required',
            'general_ledger_account' => 'required',
            'sub_major_account_group' => 'required',
            'location' => 'required',
        ];
    }
}