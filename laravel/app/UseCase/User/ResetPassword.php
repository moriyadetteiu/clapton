<?php

namespace App\UseCase\User;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

use App\UseCase\UseCase;
use App\Models\User;

class ResetPassword extends UseCase
{
    public function execute(ResetPasswordInput $input): ?string
    {
        $status = Password::reset(
            $input->getCredentials(),
            function ($user, $password) {
                $user->password = User::encryptPassword($password);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return null;
        }

        return __($status);
    }
}
