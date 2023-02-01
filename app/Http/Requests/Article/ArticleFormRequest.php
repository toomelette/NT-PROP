<?php


namespace App\Http\Requests\Article;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleFormRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    
    public function rules(){
        return [
           'article' => [
               'required',
               Rule::unique('inv_master','article'),
           ],
        ];
    }
}