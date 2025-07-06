<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\Footer;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\FooterLanguage;
use App\Http\Controllers\Controller;

class FooterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){
        $footer = Footer::first();

        $languages = Language::get();

        $footer_language = FooterLanguage::where(['footer_id' => $footer->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.website_footer', compact('footer', 'languages', 'footer_language'));
    }

    public function update(Request $request, $id){
        $rules = [
            'copyright' =>'required',
            'description' =>'required',
        ];
        $customMessages = [
            'copyright.required' => trans('admin_validation.Copyright is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $footer = Footer::first();
        $footer_language = FooterLanguage::where(['footer_id' => $footer->id, 'lang_code' => $request->lang_code])->first();

        $footer_language->copyright = $request->copyright;
        $footer_language->description = $request->description;
        $footer_language->save();

        if ($request->card_image) {
            $file_path = uploadPublicFile($request->card_image, 'uploads/website-images', $footer->payment_image);
            $footer->payment_image = $file_path;
            $footer->save();
        }


        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
