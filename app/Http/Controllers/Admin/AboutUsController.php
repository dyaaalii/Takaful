<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\AboutUs;
use App\Models\Language;
use App\Models\BecomeAuthor;
use Illuminate\Http\Request;
use App\Models\AboutUsLanguage;
use App\Http\Controllers\Controller;
use App\Models\BecomeAuthorLanguage;

class AboutUsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $about = AboutUs::with('aboutlangadmin')->first();
        $languages = Language::get();
        $about_language = AboutUsLanguage::where(['about_id' => $about->id, 'lang_code' => $request->lang_code])->first();
        return view('admin.about-us',compact('about', 'languages', 'about_language'));
    }

    public function update_aboutUs(Request $request){
        $rules = [
            'title' => 'required',
            'header1' => 'required',
            'header2' => 'required',
            'header3' => 'required',
            'name' => 'required',
            'desgination' => 'required',
            'about_us' => 'required',
        ];
        $customMessages = [
            'header1.required' => trans('admin_validation.Title is required'),
            'header1.required' => trans('admin_validation.Header is required'),
            'header2.required' => trans('admin_validation.Header is required'),
            'header3.required' => trans('admin_validation.Header is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'desgination.required' => trans('admin_validation.Designation is required'),
            'about_us.required' => trans('admin_validation.About us is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $about = AboutUs::with('aboutlangadmin')->first();

        $about_language = AboutUsLanguage::where(['about_id' => $about->id, 'lang_code' => $request->lang_code])->first();

        $about_language->name = $request->name;
        $about_language->desgination = $request->desgination;
        $about_language->title = $request->title;
        $about_language->header1 = $request->header1;
        $about_language->header2 = $request->header2;
        $about_language->header3 = $request->header3;
        $about_language->about_us = $request->about_us;
        $about_language->save();

        if($request->banner_image){
            $file_path = uploadPublicFile($request->banner_image, 'uploads/custom-images', $about->banner_image);
            $about->banner_image = $file_path;
            $about->save();
        }


        if($request->image){
            $file_path = uploadPublicFile($request->image, 'uploads/custom-images', $about->image);
            $about->image = $file_path;
            $about->save();
        }

        if($request->signature){
            $file_path = uploadPublicFile($request->signature, 'uploads/custom-images', $about->signature);
            $about->signature = $file_path;
            $about->save();
        }

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function become_author(Request $request){
        $become_author = BecomeAuthor::with('becomelangadmin')->first();
        $languages = Language::get();
        $become_language = BecomeAuthorLanguage::where(['become_id' => $become_author->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.become_author',compact('become_author','languages','become_language'));
    }

    public function update_become_author(Request $request){

        $rules = [
            'title' => 'required',
            'header1' => 'required',
            'header2' => 'required',
            'description' => 'required',
            'item1' => 'required',
            'item2' => 'required',
            'item3' => 'required',
            'item4' => 'required',
            'name' => 'required',
            'desgination' => 'required',
        ];

        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'header1.required' => trans('admin_validation.Header is required'),
            'header2.required' => trans('admin_validation.Header is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'item1.required' => trans('admin_validation.Item is required'),
            'item2.required' => trans('admin_validation.Item is required'),
            'item3.required' => trans('admin_validation.Item is required'),
            'item4.required' => trans('admin_validation.Item is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'desgination.required' => trans('admin_validation.Designation is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $become_author = BecomeAuthor::with('becomelangadmin')->first();
        $become_language = BecomeAuthorLanguage::where(['become_id' => $become_author->id, 'lang_code' => $request->lang_code])->first();

        $become_language->title = $request->title;
        $become_language->name = $request->name;
        $become_language->desgination = $request->desgination;
        $become_language->header1 = $request->header1;
        $become_language->header2 = $request->header2;
        $become_language->description = $request->description;
        $become_language->item1 = $request->item1;
        $become_language->item2 = $request->item2;
        $become_language->item3 = $request->item3;
        $become_language->item4 = $request->item4;
        $become_language->save();

        if($request->bg_image){
            $file_path = uploadPublicFile($request->bg_image, 'uploads/website-images', $become_author->bg_image);
            $become_author->bg_image = $file_path;
            $become_author->save();
        }

        if($request->image1){
            $file_path = uploadPublicFile($request->image1, 'uploads/website-images', $become_author->image1);
            $become_author->image1 = $file_path;
            $become_author->save();
        }

        if($request->image2){
            $file_path = uploadPublicFile($request->image2, 'uploads/website-images', $become_author->image2);
            $become_author->image2 = $file_path;
            $become_author->save();
        }

        if($request->image){
            $file_path = uploadPublicFile($request->image, 'uploads/website-images', $become_author->image);
            $become_author->image = $file_path;
            $become_author->save();
        }

        if($request->signature){
            $file_path = uploadPublicFile($request->signature, 'uploads/website-images', $become_author->signature);
            $become_author->signature = $file_path;
            $become_author->save();
        }

        if($request->icon1){
            $file_path = uploadPublicFile($request->icon1, 'uploads/website-images', $become_author->icon1);
            $become_author->icon1 = $file_path;
            $become_author->save();
        }

        if($request->icon2){
            $file_path = uploadPublicFile($request->icon2, 'uploads/website-images', $become_author->icon2);
            $become_author->icon2 = $file_path;
            $become_author->save();
        }

        if($request->icon3){
            $file_path = uploadPublicFile($request->icon3, 'uploads/website-images', $become_author->icon3);
            $become_author->icon3 = $file_path;
            $become_author->save();
        }

        if($request->icon4){
            $file_path = uploadPublicFile($request->icon4, 'uploads/website-images', $become_author->icon4);
            $become_author->icon4 = $file_path;
            $become_author->save();
        }



        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
