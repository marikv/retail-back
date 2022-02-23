<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $User = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        // $token = $User->createToken('myapptokenforretail')->plainTextToken;

        return response([
            'success' => true,
            'data' => [
                'user' => $User,
                // 'token' => $token,
            ],
        ], 201);
    }

    public function addNewUserForDealer(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|min:5',
            'password' => 'required|string|min:8',
            'dealer_id' => 'required|integer',
        ]);

        /* @var $Dealer Dealer */
        $Dealer = Dealer::findOrFail($fields['dealer_id']);

        /* @var $User User */
        $User = User::create([
            'name' => $fields['username'],
            'email' => $fields['username'],
            'password' => bcrypt($fields['password']),
        ]);

        $User->dealer_id = $fields['dealer_id'];
        $User->role_id = User::USER_ROLE_DEALER;
        $User->avatar = $Dealer->logo;
        $User->save();

        return response([
            'success' => true,
            'data' => [
                'user' => $User,
            ],
        ], 201);
    }

    public function editUserForDealer($id, Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|min:5',
            'user_id' => 'required|integer',
        ]);

        /* @var $Dealer Dealer */
        $Dealer = Dealer::findOrFail($id);

        /* @var $User User */
        $User = User::where('id', '=', $fields['user_id'])->where('dealer_id', '=', $id)->first();

        $User->role_id = User::USER_ROLE_DEALER;
        $User->email = $fields['username'];
        $User->name = $fields['username'];
        if ($request->password) {
            $User->password = bcrypt($request->password);
        }
        $User->save();

        return response([
            'success' => true,
            'data' => [],
        ], 201);
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function getUsersByDealer(int $id)
    {
        $Users = [];

        if ($id > 0) {

            $Users = User::whereNull('deleted')
                ->where('dealer_id', '=', $id)
                ->where('role_id', '=', User::USER_ROLE_DEALER)
                ->orderBy('id', 'desc')
                ->get()
            ;
        }

        return response([
            'success' => true,
            'data' => $Users,
        ], 200);
    }


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $User = User::where('email', $fields['email'])->first();

        if (!$User || !Hash::check($fields['password'], $User->password)) {
            return  response([
                'success' => false,
                'data' => [
                    'message' => 'Utilizatorul nu a fost găsit'
                ],
            ], 401);
        }

        $token = $User->createToken('myapptokenforretail')->plainTextToken;

        return response([
            'success' => true,
            'data' => [
                'user' => $User,
                'token' => $token,
            ],
        ], 200);
    }


    public function checkToken(Request  $request)
    {
        $User = auth() && auth()->user() ? auth()->user() : null;
        return response([
            'success' => $User && $User->tokens(),
            'data' => [
                'user' => $User,
            ]
        ], 200);
    }


    public function logout(Request  $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'logout cu success'
        ], 200);
    }
}
