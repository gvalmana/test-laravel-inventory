<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntradaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            "entradas"=>["required"],
            "entradas.*.producto_id"=>['numeric','required','exists:productos,id'],
            "entradas.*.cantidad" => ['numeric','required','min:1'],
        ];
    }
}
