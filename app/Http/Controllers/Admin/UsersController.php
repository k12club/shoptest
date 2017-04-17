<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller {

    /**
     * Users
     * URL: /admin/users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Update User
     * URL: /admin/users/{user} (POST)
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $user) {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|max:255|email|unique:users,email,' . $user['id'],
            'password' => 'sometimes|min:6|max:255',
            'type' => 'required|in:user,admin'
        ]);

        foreach ([
                     'first_name',
                     'last_name',
                     'email',
                     'type',
                 ] as $field) {
            if (isset($data[$field]) && $data[$field] != $user->{$field}) {
                $user->{$field} = $data[$field];
            }
        }

        // Set the new password
        if (!empty($data['password'])) {
            $user->{'password'} = bcrypt($data['password']);
        }

        $user->save();

        return redirect(route('admin.users.user', $user['id']))->with('alert-success', 'Your user information has been updated successfully.');
    }
}