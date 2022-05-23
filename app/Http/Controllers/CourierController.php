<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    //
    public function create(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|file'
        ]);

        $response = [];

        $userId = auth()->user()->id;

        $mytime = Carbon::now();

        $serialNumber = strtotime($mytime);

        $imageFullName = $request->file('image')->getClientOriginalName();

        $fileName = $serialNumber.$imageFullName;

        $fields += [
            'user_id' => $userId,
            'serial_number' => $serialNumber,
            'file_name' => $fileName
        ];
        $fields['image'] = $imageFullName;
        try {
            $courier = Courier::create($fields);
            $request->file('image')->storeAs('public/images', $fileName);

            $response = [
                'data' => $courier,
                'message' => 'Successfully uploaded'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'error' => $th->getMessage(),
                'message' => 'Failed to upload'
            ];
            return response($response, 500);

        }

    }


    public function list(){
        $response = [];
        try {
            $Couriers = Courier::get();
            $response = [
                'data' => $Couriers,
                'message' => 'Courier list was successful'
            ];
            return response($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => $th->getMessage(),
                'message' => 'could not list Couriers'
            ];
            return response($response, 500);
        }
    }

    public function update(Request $request){
        $fields = $request->validate([
            'courier_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        $response = [];

        $courier = Courier::find($fields['courier_id']);

        if ($courier) {
            try {
                $courier->name = $fields['name'];
                $courier->description = $fields['description'];
                $courier->save();

                $response = [
                    'data' => $courier,
                    'message' => "successful update"
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
                'error' => 'the courier does not exit',
                'message' => 'the courier does not exit'
            ];

            return response($response, 403);
        }
    }

    public function delete(Request $request){

        $response = [];
        if (Courier::find($request->route('id'))) {
            try {
                Courier::where('id', $request->route('id'))->delete();
                $response = [
                    'message' => 'Courier delete was successful'
                ];

                return response($response, 200);
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => 'could not delete courier'
                ];
                return response($response, 500);
            }

        }else{
            $response = [
                'error' => 'cant delete this courier',
                'message' => 'Cant delete this courier'
            ];

            return response($response, 403);
        }
    }
}
