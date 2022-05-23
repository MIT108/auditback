<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required'
        ]);

        $response = [];

        if ($this->checkDepartementName($fields['name'])) {

            try {
                $Departement = Departement::create($fields);
                $response = [
                    'data' => $Departement,
                    'message' => 'Departement created successfully'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not create Departement'
                ];
                return response($response, 500);
            }

        }else {
            $response = [
                'error' => 'this Departement name has already been assigned',
                'message' => 'Departement name has already been assigned'
            ];
            return response($response, 403);
        }

    }

    public function update(Request $request){
        $fields = $request->validate([
            'departement_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required'
        ]);
        if ($fields['departement_id'] != 1) {
            $Departement = Departement::find($fields['departement_id']);
            $response = [];
            if ($Departement) {

                if ($Departement->name == $fields['name']) {
                    try {
                        $Departement->description = $fields["description"];
                        $Departement->user_id = $fields["user_id"];
                        $Departement->save();

                        $response = [
                            'data' => $Departement,
                            'message' => 'Departement update was successful'
                        ];
                        return response($response, 200);
                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'could not update Departement'
                        ];

                        return response($response, 500);
                    }
                }else{
                    if ($this->checkDepartementName($fields['name'])) {
                        try {
                            $Departement->name = $fields["name"];
                            $Departement->description = $fields["description"];
                            $Departement->user_id = $fields["user_id"];
                            $Departement->save();

                            $response = [
                                'data' => $Departement,
                                'message' => 'Departement update was successful'
                            ];
                            return response($response, 200);

                        } catch (\Throwable $th) {
                            $response = [
                                'error' => $th->getMessage(),
                                'message' => 'could not update Departement'
                            ];
                            return response($response, 500);
                        }

                    }else {
                        $response = [
                            'error' => 'this Departement name has already been assigned',
                            'message' => 'this Departement name has already been assigned'
                        ];

                        return response($response, 403);
                    }
                }
            }else {
                $response = [
                    'error' => 'Departement does not exit',
                    'message' => 'Departement does not exit'
                ];

                return response($response, 403);
            }
        }else{
            $response = [
                'error' => 'could not update Departement',
                'message' => 'could not update Departement'
            ];
            return response($response, 403);
        }

    }

    public function listAll(){
        $response = [];
        try {
            $Departements = Departement::get();
            $response = [
                'data' => $Departements,
                'message' => 'Departement list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list Departements'
            ];
            return response($response, 500);
        }
    }

    public function delete(Request $request){
        $response = [];
        if (Departement::find($request->route('id'))) {
            try {
                Departement::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'Departement delete was successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete Departement'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'cant delete this Departement',
                'message' => 'Cant delete this Departement'
            ];

            return response($response, 403);
        }

    }

    public function checkDepartementName($name){
        if (Departement::where('name', $name)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }

}
