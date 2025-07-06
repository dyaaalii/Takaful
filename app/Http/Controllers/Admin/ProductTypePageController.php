<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\ProductTypePage;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Models\ProductTypePageLanguage;

class ProductTypePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){
        $productType = ProductTypePage::first();

        $languages = Language::get();

        $product_type_language = ProductTypePageLanguage::where(['product_type_id' => $productType->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.product_type_page', compact('productType', 'languages', 'product_type_language'));
    }

    public function update(Request $request, $id)
    {
        $productType = ProductTypePage::find($id);

        $product_type_language = ProductTypePageLanguage::where(['product_type_id' => $productType->id, 'lang_code' => $request->lang_code])->first();

        $rules = [
            'title'=>'required',
            'description'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product_type_language->title = $request->title;
        $product_type_language->description = $request->description;
        $product_type_language->save();

        if ($request->image) {
            $file_path = uploadPublicFile($request->image, 'uploads/website-images', $productType->image);
            $productType->image = $file_path;
            $productType->save();
        }

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
