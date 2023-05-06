<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Adress;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('address')->orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        $current_user = User::create([
            'name'     => $request['name'],
            'email'  => $request['email'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
        ]);

        Adress::create([
            'user_id'     => $current_user['id'],
            'street'  => $request['address']['street'],
            'city' => $request['address']['city'],
        ]);

        return response()->json('User created');
    }

    public function update(StoreUserRequest $request)
    {
        $user = User::with('address')->where('id', $request['id'])->first();

        $user->update([
            'name'     => $request['name'],
            'email'  => $request['email'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
        ]);
        $user->address->update([
            'user_id'     => $user['id'],
            'street'  => $request['address']['street'],
            'city' => $request['address']['city'],
        ]);

        return response()->json($user);
    }

    public function destroy(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        $user->delete();

        return response()->json('User deleted');
    }
}
