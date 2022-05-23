<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'role_id' => 'required',
            'password' => 'required|string'
        ]);


        $response = [];

        if ($this->checkEmail($fields['email'])) {

            $fields['password'] = bcrypt($fields['password']);

            try {
                $user = User::create($fields);
                $response = [
                    'data' => $user,
                    'message' => 'Role created successfully'
                ];

                return response($response, 200);

            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not create role'
                ];
                return response($response, 500);
            }

        }else {
            $response = [
                'error' => 'this user email has already been assigned',
                'message' => 'this user email has already been assigned'
            ];
            return response($response, 403);
        }

    }

    public function update(Request $request){
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = User::find(auth()->user()->id);
        $response = [];
        if ($user) {

            if ($user->email == $fields['email']) {
                try {
                    $user->name = $fields["name"];
                    $user->save();

                    $response = [
                        'data' => $user,
                        'message' => 'User update was successful'
                    ];
                    return response($response, 200);
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'could not update user'
                    ];

                    return response($response, 500);
                }
            }else{
                if ($this->checkEmail($fields['email'])) {
                    try {
                        $user->name = $fields["name"];
                        $user->email = $fields["email"];
                        $user->save();

                        $response = [
                            'data' => $user,
                            'message' => 'user update was successful'
                        ];
                        return response($response, 200);

                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'could not update user'
                        ];
                        return response($response, 500);
                    }

                }else {
                    $response = [
                        'error' => 'this email has already been assigned',
                        'message' => 'this email has already been assigned'
                    ];

                    return response($response, 403);
                }
            }
        }else {
            $response = [
                'error' => 'User does not exit',
                'message' => 'User does not exit'
            ];

            return response($response, 403);
        }

    }

    public function listAll(){
        $response = [];
        try {
            $users = User::get();
            $response = [
                'data' => $users,
                'message' => 'users list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list users'
            ];
            return response($response, 500);
        }
    }

    public function delete(Request $request){
        $response = [];
        if (User::find($request->route('id'))) {
            try {
                User::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'user delete was successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete user'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'cant delete this user',
                'message' => 'Cant delete this user'
            ];

            return response($response, 403);
        }

    }

    public function checkEmail($email){
        if (User::where('email', $email)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }

}
