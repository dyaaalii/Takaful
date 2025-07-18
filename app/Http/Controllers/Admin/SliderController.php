<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\SliderLanguage;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){
        $slider = Slider::with('sliderlangadmin')->first();
        $sliderlanguage = SliderLanguage::where(['slider_id' => $slider->id, 'lang_code' => $request->lang_code])->first();
        $languages = Language::get();
        $setting = Setting::first();
        $selected_theme = $setting->selected_theme;

        return view('admin.create_slider', compact('slider','selected_theme', 'languages', 'sliderlanguage'));
    }

    public function update(Request $request, $id){
        $rules = [
            'home1_title' => 'required',
            'home2_title' => 'required',
            'home3_title' => 'required',
            'home2_description' => 'required',
            'home3_description' => 'required',
            'total_sold' => session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'total_product' => session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'total_user' => session()->get('admin_lang') == $request->lang_code ? 'required':'',
        ];
        $customMessages = [
            'home1_title.required' => trans('admin_validation.Title is required'),
            'home2_title.required' => trans('admin_validation.Title is required'),
            'home3_title.required' => trans('admin_validation.Title is required'),
            'home2_description.required' => trans('admin_validation.Description is required'),
            'home3_description.required' => trans('admin_validation.Description is required'),
            'total_sold.required' => trans('admin_validation.Total sold is required'),
            'total_product.required' => trans('admin_validation.Total product is required'),
            'total_user.required' => trans('admin_validation.Total user is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $slider = Slider::find($id);
        $sliderlanguage = SliderLanguage::where(['slider_id' => $slider->id, 'lang_code' => $request->lang_code])->first();

        // Array of slider fields to handle
        $sliderFields = [
            'home1_bg' => 'home1_bg',
            'home2_bg' => 'home2_bg',
            'home2_image' => 'home2_image',
            'home3_image' => 'home3_image',
            'home3_bg' => 'home3_bg',
        ];

        // Loop through the fields and handle image uploads
        foreach ($sliderFields as $requestField => $sliderField) {
            if ($request->$requestField) {
                $existing_slider = $slider->$sliderField;
                $file_path = uploadPublicFile($request->$requestField, 'uploads/website-images', $existing_slider);
                $slider->$sliderField = $file_path;
                $slider->save();
            }
        }


        if($request->total_sold){
            $slider->total_sold = $request->total_sold;
        }

        if($request->total_product){
            $slider->total_product = $request->total_product;
        }

        if($request->total_user){
            $slider->total_user = $request->total_user;
        }

        $slider->save();

        $sliderlanguage->home1_title = $request->home1_title;
        $sliderlanguage->home2_title = $request->home2_title;
        $sliderlanguage->home3_title = $request->home3_title;
        $sliderlanguage->home2_description = $request->home2_description;
        $sliderlanguage->home3_description = $request->home3_description;
        $sliderlanguage->save();

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
