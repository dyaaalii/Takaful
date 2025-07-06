<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\Language;
use App\Models\ErrorPage;
use Illuminate\Http\Request;
use App\Models\ErrorPageLanguage;
use App\Http\Controllers\Controller;

class ErrorPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){
        $errorpage = ErrorPage::first();
        return view('admin.error_page', compact('errorpage'));
    }

    public function update(Request $request, $id)
    {
        $errorPage = ErrorPage::first();
        $rules = [
            'title'=>'required',
            'button_text'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'button_text.required' => trans('admin_validation.Button text is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $errorPage->title = $request->title;
        $errorPage->button_text = $request->button_text;
        $errorPage->save();

        if ($request->image) {
            $file_path = uploadPublicFile($request->image, 'uploads/website-images', $errorPage->image);
            $errorPage->image = $file_path;
            $errorPage->save();
        }

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
