<?php


namespace App\Http\Requests\RFQ;


use Illuminate\Foundation\Http\FormRequest;

class RFQFormRequest extends FormRequest
{
 public function authorize(){
     return true;
 }

 public function rules(){
     return [
        'rfq_deadline' => 'required|date_format:Y-m-d',
     ];
 }
}