<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $response = [];

        if ($this->checkRoleName($fields['name'])) {

            try {
                $role = Role::create($fields);
                $response = [
                    'data' => $role,
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
                'error' => 'this role name has already been assigned',
                'message' => 'Role name has already been assigned'
            ];
            return response($response, 403);
        }

    }

    public function update(Request $request){
        $fields = $request->validate([
            'role_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($fields['role_id'] != 1) {
            $role = Role::find($fields['role_id']);
            $response = [];
            if ($role) {

                if ($role->name == $fields['name']) {
                    try {
                        $role->description = $fields["description"];
                        $role->save();

                        $response = [
                            'data' => $role,
                            'message' => 'role update was successful'
                        ];
                        return response($response, 200);
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'could not update role'
                        ];

                        return response($response, 500);
                    }
                }else{
                    if ($this->checkRoleName($fields['name'])) {
                        try {
                            $role->name = $fields["name"];
                            $role->description = $fields["description"];
                            $role->save();

                            $response = [
                                'data' => $role,
                                'message' => 'role update was successful'
                            ];
                            return response($response, 200);

                        } catch (\Throwable $th) {
                            $response = [
                                'error' => $th->getMessage(),
                                'message' => 'could not update role'
                            ];
                            return response($response, 500);
                        }

                    }else {
                        $response = [
                            'error' => 'this role name has already been assigned',
                            'message' => 'this role name has already been assigned'
                        ];

                        return response($response, 403);
                    }
                }
            }else {
                $response = [
                    'error' => 'role does not exit',
                    'message' => 'role does not exit'
                ];

                return response($response, 403);
            }
        }else{
            $response = [
                'error' => 'could not update role',
                'message' => 'could not update role'
            ];
            return response($response, 403);
        }

    }

    public function listAll(){
        $response = [];
        try {
            $roles = Role::where('id', '!=', 1)->where('id', '!=', 2)->where('id', '!=', 3)->get();
            $response = [
                'data' => $roles,
                'message' => 'role list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list roles'
            ];
            return response($response, 500);
        }
    }

    public function list(){
        $response = [];
        try {
            $roles = Role::get();
            $response = [
                'data' => $roles,
                'message' => 'role list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list roles'
            ];
            return response($response, 500);
        }
    }

    public function delete(Request $request){
        $response = [];
        if (Role::find($request->route('id'))) {
            try {
                Role::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'role delete was successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete role'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'cant delete this role',
                'message' => 'Cant delete this role'
            ];

            return response($response, 403);
        }

    }

    public function checkRoleName($name){
        if (Role::where('name', $name)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }


}
