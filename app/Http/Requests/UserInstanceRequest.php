<?php

namespace App\Http\Requests;

class UserInstanceRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "{$this->input}.user_id" => "required",
            "{$this->input}.instances" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            "{$this->input}.*.required" => ":attribute harus diinputkan.",
        ];
    }

    public function attributes(): array
    {
        return [
            "{$this->input}.user_id" => 'User',
            "{$this->input}.instances" => 'Instance'
        ];
    }
}
