<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use  Image;
use File;
use Str;
class PartnerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $partners = Partner::all();
        return view('admin.partner',compact('partners'));
    }

    public function create()
    {
        return view('admin.create_partner');
    }


    public function store(Request $request)
    {
        $rules = [
            'logo' => 'required'
        ];
        $customMessages = [
            'logo.required' => trans('admin_validation.Logo is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $partner = new Partner();


        if ($request->logo) {
            $file_path = uploadPublicFile($request->logo, 'uploads/custom-images');
            $partner->logo = $file_path;
        }

        $partner->link = $request->link;
        $partner->status = $request->status;
        $partner->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.partner.index')->with($notification);
    }


    public function edit($id)
    {
        $partner = Partner::find($id);
        return view('admin.edit_partner',compact('partner'));
    }


    public function update(Request $request, $id)
    {
        $partner = Partner::find($id);

        if ($request->logo) {
            $file_path = uploadPublicFile($request->logo, 'uploads/custom-images', $partner->logo);
            $partner->logo = $file_path;
            $partner->save();
        }

        $partner->link = $request->link;
        $partner->status = $request->status;
        $partner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.partner.index')->with($notification);
    }


    public function destroy($id)
    {
        $partner = Partner::find($id);
        $old_logo = $partner->logo;
        $partner->delete();

        deleteFile($old_logo);

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.partner.index')->with($notification);
    }

    public function changeStatus($id){
        $partner = Partner::find($id);
        if($partner->status == 1){
            $partner->status = 0;
            $partner->save();
            $message = trans('admin_validation.InActive Successfully');
        }else{
            $partner->status = 1;
            $partner->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
