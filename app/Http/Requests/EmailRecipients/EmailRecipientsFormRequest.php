<?php


namespace App\Http\Requests\EmailRecipients;


use Illuminate\Foundation\Http\FormRequest;

class EmailRecipientsFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
           'email.*' => 'required|email',
        ];
    }
}