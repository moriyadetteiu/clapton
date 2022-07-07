<?php

namespace App\UseCase\User;

use Illuminate\Support\Facades\Password;

use App\UseCase\UseCase;

class ForgetPassword extends UseCase
{
    public function execute(ForgetPasswordInput $input): ?string
    {
        $status = Password::sendResetLink([
            'email' => $input->get('email')
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return null;
        }

        return __($status);
    }
}
