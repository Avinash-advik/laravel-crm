<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\CustomField;
use Carbon\Carbon;

class CustomFieldController extends Controller
{
    public function index(){
        $data['title'] = 'Custom Field Listing';
        return view('pages.customfieldlist', $data);
    }

    public function listing(Request $request, $search=""){
        $data = CustomField::latest()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }

        try {
            $insert_array = array(
                'name' => ucwords($request->name),
                'type' => $request->type,
                'created_at'=>Carbon::now(),
            );

            CustomField::insert($insert_array);
            return response()->json(['success' => true, 'msg' => 'Custom Field added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id){
        try {
            $result = CustomField::where('id', $id)->first();
            return response()->json(['success' => true, 'msg' => 'Custom Field Fetched Successfully!', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }

        try {
            $update_array = array(
                'name' => ucwords($request->name),
                'type' => $request->type,
                'updated_at'=>Carbon::now(),
            );

            CustomField::where('id',$id)->update($update_array);
            return response()->json(['success' => true, 'msg' => 'Custom Field updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            $CustomField = CustomField::findOrFail($id);
            $CustomField->delete();
            return response()->json(['success' => true, 'msg' => 'Custom Field deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
