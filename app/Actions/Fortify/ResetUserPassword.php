<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the given user's password using the token & email.
     *
     * @param  array<string, string>  $input
     */
    public function reset(array $input): void
    {
        Validator::make($input, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => $this->passwordRules(),
        ])->validate();

        $status = Password::reset(
            $input,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }
}