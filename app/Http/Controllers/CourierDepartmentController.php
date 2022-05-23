<?php

namespace App\Http\Controllers;

use App\Models\CourierDepartment;
use Illuminate\Http\Request;

class CourierDepartmentController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'description' => 'required|string',
            'courier_id' => 'required',
            'departement_id' => 'required'
        ]);
        $response = [];

        if($this->checkIds($fields['courier_id'], $fields['departement_id'])){

            try {
                $data = CourierDepartment::create($fields);
                $response = [
                    'data' => $data,
                    'message' => 'successfully added'
                ];
                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'an error occurred while adding department'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'message' => 'this courier is already associated to this department'
            ];
            return response($response, 403);
        }


    }

    public function list(){

        $response = [];
        try {
            $data = CourierDepartment::get();
            $response = [
                'data' => $data,
                'message' => 'successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list'
            ];
            return response($response, 500);
        }

    }

    public function update(Request $request) {
        $fields = $request->validate([
            'cour_dep_id' => 'required',
            'description' => 'required',
        ]);

        $response = [];

        $courier = CourierDepartment::find($fields['cour_dep_id']);

        if ($courier) {
            try {
                $courier->description = $fields['description'];
                $courier->save();

                $response = [
                    'data' => $courier,
                    'message' => "successful"
                ];
                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'error'
                ];

                return response($response, 500);
            }
        }else{
            $response = [
                'error' => 'does not exit',
                'message' => 'does not exit'
            ];

            return response($response, 403);
        }

    }

    public function delete(Request $request) {
        $response = [];
        if (CourierDepartment::find($request->route('id'))) {
            try {
                CourierDepartment::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'error while deleting'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'does not exist',
                'message' => 'does not exist'
            ];

            return response($response, 403);
        }

    }

    public function checkIds($courierId, $departmentId){

        if (CourierDepartment::where('courier_id', $courierId)->where('departement_id', $departmentId)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
