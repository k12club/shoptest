<?php

namespace App\Policies;

use App\User;
use App\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given address can be viewed by this user.
     *
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function view(User $user, Address $address)
    {
        return $user['type'] == 'admin' || $user['id'] == $address['user_id'];
    }
}