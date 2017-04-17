<?php

namespace App\Policies;

use App\User;
use App\PaymentSource;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentSourcePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given payment source can be viewed by this user.
     *
     * @param User $user
     * @param PaymentSource $paymentSource
     * @return bool
     */
    public function view(User $user, PaymentSource $paymentSource)
    {
        return $user['type'] == 'admin' || $user['id'] == $paymentSource['user_id'];
    }
}