<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobPostRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:75',
            'description' => 'required|max:600',
            'roles' => 'required|max:600',
            'type' => 'required',
            'category_id' => 'required',
            'position' => 'required',
            'last_date' => 'required'
        ];
    }
}
