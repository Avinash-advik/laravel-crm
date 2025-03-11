<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Contact;
use App\Models\CustomField;
use App\Models\ContactCustomValue;
use Carbon\Carbon;

class ContactController extends Controller
{
    //
    public function index(){
        $data['title'] = 'Contact Listing';
        $data['custom_fields'] = CustomField::latest()->get();
        return view('pages.contactlist', $data);
    }

    public function listing(Request $request, $search=""){
        $data['results'] = Contact::latest()->with('mergedInto');

        if($search!=''){
            $data['results'] = $data['results']->where(function($query) use ($search){
                $query->orWhere('name','LIKE','%'.$search.'%')
                ->orWhere('email','LIKE','%'.$search.'%')
                ->orWhere('gender', 'LIKE', '%'.$search.'%');});
        }
        $data['results'] =  $data['results']->get();

        return response()->json(['success' => true, 'data' => $data['results']]);
    }

    public function mastercontact(){
        $data['results'] = Contact::latest()->whereNull('merged_into')->get();
        return response()->json(['success' => true, 'data' => $data['results']]);
    }

    public function merge(Request $request){
        $validator = Validator::make($request->all(),[
            'master' => 'required|exists:contacts,id',
        ]);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }
        try {
            $masterContact = Contact::findOrFail($request->master);
            $secondaryContact = Contact::findOrFail($request->secondary_id);

            // Merge emails (add secondary contact email if different)
            if ($secondaryContact->email !== $masterContact->email) {
                $masterContact->email .= ', ' . $secondaryContact->email;
            }

            // Merge phone numbers (add secondary phone numbers if different)
            if ($secondaryContact->phone !== $masterContact->phone) {
                $masterContact->phone .= ', ' . $secondaryContact->phone;
            }

            // Save the master contact
            $masterContact->save();

            // Merge Custom Fields
            $secondaryFields = ContactCustomValue::where('contact_id', $request->secondary_id)->get();
            foreach ($secondaryFields as $field) {
                if($field->value != ""){
                    ContactCustomValue::updateOrCreate(
                        ['contact_id' => $request->master, 'custom_field_id' => $field->custom_field_id],
                        ['value' => $field->value] // Customize merging logic if needed
                    );
                }
            }

            // Mark the secondary contact as merged
            $secondaryContact->merged_into = $request->master;
            $secondaryContact->save();

            return response()->json(['success' => true, 'msg' => 'Contact Merged Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'gender' => 'required',
            'profile_image' => 'nullable|mimes:png,jpg,jpeg,webp',
        ]);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }

        try {
            $insert_array = array(
                'name' => ucwords($request->name),
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'created_at'=>Carbon::now(),
            );

            if($request->file('profile_image')){
                $file = $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();

                $filename = time().'.'.$extension;

                $path = 'uploads/profile/';
                $file->move($path, $filename);
                $insert_array['profile_image'] = $path.$filename;
            }

            if($request->file('additional_file')){
                $file = $request->file('additional_file');
                $extension = $file->getClientOriginalExtension();

                $additionalfilename = time().'.'.$extension;

                $additionalpath = 'uploads/additionalfiles/';
                $file->move($additionalpath, $additionalfilename);
                $insert_array['additional_file'] = $additionalpath.$additionalfilename;
            }

            $contactid = Contact::insertGetId($insert_array);
            if(isset($request->custom)){
                $insertCustomField = array();
                // custom field insert
                foreach($request->custom as $k => $v){
                    $custom_field_id = explode("_",$k);
                    array_push($insertCustomField, array(
                        'contact_id' => $contactid,
                        'custom_field_id' => $custom_field_id[0],
                        'value' => $v,
                        'created_at'=>Carbon::now(),
                    ));
                }
                ContactCustomValue::insert($insertCustomField);
            }

            return response()->json(['success' => true, 'msg' => 'Contact added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id){
        try {
            $result = Contact::where('id', $id)->with('mergedContacts')->with('contactCustomValues.customField')->first();
            return response()->json(['success' => true, 'msg' => 'Contact Fetched Successfully!', 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email'   => 'required|array',
            'email.*' => 'required|email',
            'phone' => 'required|array',
            'phone.*' => 'required|regex:/^[0-9]{10,15}$/',
            'gender' => 'required',
            'profile_image' => 'nullable|mimes:png,jpg,jpeg,webp',
        ]);

        if($validator->fails()){
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }

        try {
            $update_array = array(
                'name' => ucwords($request->name),
                'email' => implode(",",$request->email),
                'phone' => implode(",",$request->phone),
                'gender' => $request->gender,
                'updated_at'=>Carbon::now(),
            );
            $contact = Contact::findOrFail($id);

            if ($request->file('profile_image')){
                $file = $request->file('profile_image');
                $extension = $file->getClientOriginalExtension();

                $filename = time().'.'.$extension;

                $path = 'uploads/profile/';
                $file->move($path, $filename);
                $update_array['profile_image'] = $path.$filename;

                if(File::exists($contact->profile_image)){
                    File::delete($contact->profile_image);
                }
            }

            if($request->file('additional_file')){
                $file = $request->file('additional_file');
                $extension = $file->getClientOriginalExtension();

                $additionalfilename = time().'.'.$extension;

                $additionalpath = 'uploads/additionalfiles/';
                $file->move($path, $filename);
                $update_array['additional_file'] = $additionalpath.$additionalfilename;

                if(File::exists($contact->additional_file)){
                    File::delete($contact->additional_file);
                }
            }

            Contact::where('id',$id)->update($update_array);

            $insertCustomField = array();

            foreach ($request->custom as $field_key => $value) {
                $custom_field_id = explode("_",$field_key);
                ContactCustomValue::updateOrCreate(
                    ['contact_id' => $id, 'custom_field_id' => $custom_field_id[0]],
                    ['value' => $value]
                );
            }

            return response()->json(['success' => true, 'msg' => 'Contact updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id){
        try {
            $contact = Contact::findOrFail($id);
            if(File::exists($contact->profile_image)){
                File::delete($contact->profile_image);
            }
            if(File::exists($contact->additional_file)){
                File::delete($contact->additional_file);
            }

            $contact->delete();
            return response()->json(['success' => true, 'msg' => 'Contact deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
