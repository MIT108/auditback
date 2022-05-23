<?php

namespace App\Http\Controllers;

use App\Models\Polytic;
use Illuminate\Http\Request;

class PolyticController extends Controller
{
    //

    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'departement_id' => 'required'
        ]);

        $response = [];

        if ($this->checkPolyticName($fields['name'], $fields['departement_id'])) {

            try {
                $Polytic = Polytic::create($fields);
                $response = [
                    'data' => $Polytic,
                    'message' => 'successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not create Polytic'
                ];
                return response($response, 500);
            }

        }else {
            $response = [
                'error' => 'this polytic  name has already been assigned',
                'message' => 'polytic  name has already been assigned'
            ];
            return response($response, 403);
        }

    }

    public function update(Request $request){
        $fields = $request->validate([
            'polytic_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'departement_id' => 'required'
        ]);
        $polytic = Polytic::find($fields['polytic_id']);
        $response = [];
        if ($polytic) {

            if ($polytic->name == $fields['name']) {
                try {
                    $polytic->description = $fields["description"];
                    $polytic->departement_id = $fields["departement_id"];
                    $polytic->save();

                    $response = [
                        'data' => $polytic,
                        'message' => 'polytic update was successful'
                    ];
                    return response($response, 200);
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => 'could not update polytic'
                    ];

                    return response($response, 500);
                }
            }else{
                if ($this->checkpolyticName($fields['name'], $fields['departement_id'])) {
                    try {
                        $polytic->name = $fields["name"];
                        $polytic->description = $fields["description"];
                        $polytic->departement_id = $fields["departement_id"];
                        $polytic->save();

                        $response = [
                            'data' => $polytic,
                            'message' => 'polytic update was successful'
                        ];
                        return response($response, 200);

                    } catch (\Throwable $th) {
                        $response = [
                            'error' => $th->getMessage(),
                            'message' => 'could not update polytic'
                        ];
                        return response($response, 500);
                    }

                }else {
                    $response = [
                        'error' => 'this polytic name has already been assigned',
                        'message' => 'this polytic name has already been assigned'
                    ];

                    return response($response, 403);
                }
            }
        }else {
            $response = [
                'error' => 'polytic does not exit',
                'message' => 'polytic does not exit'
            ];

            return response($response, 403);
        }
    }

    public function listAll(Request $request){
        $response = [];
        try {
            $polytics = Polytic::where('departement_id', $request->route('departement_id'))->get();
            $response = [
                'data' => $polytics,
                'message' => 'role list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list polytics'
            ];
            return response($response, 500);
        }
    }

    public function delete(Request $request){
        $response = [];
        if (Polytic::find($request->route('id'))) {
            try {
                Polytic::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'Polytic delete was successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete Polytic'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'cant delete this Polytic',
                'message' => 'Cant delete this Polytic'
            ];

            return response($response, 403);
        }

    }

    public function checkPolyticName($name, $departement_id){
        if (Polytic::where('name', $name)->where('departement_id', $departement_id)->count() > 0) {
            return false;
        }else {
            return true;
        }
    }
}
