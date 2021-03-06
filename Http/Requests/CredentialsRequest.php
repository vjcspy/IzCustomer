<?php namespace Modules\IzCustomer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CredentialsRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|max:255'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'email.required'    => 'Email là bắt buộc',
            'email.unique'    => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là bắt buộc',
        ];
    }

}
