<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use File;

class AdController extends Controller
{
    public function ad(){
        $ad=Ad::first();
        return view('admin.ad', compact('ad'));
    }

    public function updateAd(Request $request){
        $rules = [
            'link'=>'required',
        ];
        $customMessages = [
            'link.required' => trans('admin_validation.Link is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $ad=Ad::first();
        $ad->link=$request->link;
        $ad->status=$request->status;
        $ad->save();

        if($request->image){
            $file_path = uploadPublicFile($request->image, 'uploads/website-images', $ad->image);
            $ad->image = $file_path;
            $ad->save();
        }

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
