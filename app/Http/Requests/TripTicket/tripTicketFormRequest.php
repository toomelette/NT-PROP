<?php


namespace App\Http\Requests\TripTicket;


use Illuminate\Foundation\Http\FormRequest;

class tripTicketFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'date' => 'required',
        ];
    }
}