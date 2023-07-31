<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
{
    public User $user;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->user = User::findOrFail($this->route('id'));
        if (! hash_equals((string) $this->user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($this->user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
