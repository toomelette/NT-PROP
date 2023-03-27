<?php


namespace App\Http\Requests\CancellationRequest;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CRFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'slug' => [
                'required',
            ],
            'ref_book' => [
                'required',
            ],
            'ref_number' => [
                'required',
            ],
            'reason' => [
                'required',
            ],
        ];
    }
}