<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Models\Language;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Models\ProductItemLanguage;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class ProductItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $productItem = ProductItem::first();
        $languages = Language::get();

        $product_item_language = ProductItemLanguage::where(['item_id' => $productItem->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.product_item', compact('productItem', 'languages', 'product_item_language'));
    }



    public function update(Request $request, $id)
    {
        $productItem = ProductItem::find($id);

        $product_item_language = ProductItemLanguage::where(['item_id' => $productItem->id, 'lang_code' => $request->lang_code])->first();

        $rules = [
            'script_title'=>'required',
            'script_description'=>'required',
            'image_title'=>'required',
            'image_description'=>'required',
            'video_title'=>'required',
            'video_description'=>'required',
            'audio_title'=>'required',
            'audio_description'=>'required',
        ];
        $customMessages = [
            'script_title.required' => trans('admin_validation.Script product title is required'),
            'script_description.required' => trans('admin_validation.Script product description is required'),
            'image_title.required' => trans('admin_validation.Image product title is required'),
            'image_description.required' => trans('admin_validation.Image product description is required'),
            'video_title.required' => trans('admin_validation.Video product title is required'),
            'video_description.required' => trans('admin_validation.Video product description is required'),
            'audio_title.required' => trans('admin_validation.Audio product title is required'),
            'audio_description.required' => trans('admin_validation.Audio product description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product_item_language->script_title = $request->script_title;
        $product_item_language->script_description = $request->script_description;
        $product_item_language->image_title = $request->image_title;
        $product_item_language->image_description = $request->image_description;
        $product_item_language->video_title = $request->video_title;
        $product_item_language->video_description = $request->video_description;
        $product_item_language->audio_title = $request->audio_title;
        $product_item_language->audio_description = $request->audio_description;
        $product_item_language->save();

        if ($request->script_image) {
            $file_path = uploadPublicFile($request->script_image, 'uploads/website-images', $productItem->script_image);
            $productItem->script_image = $file_path;
            $productItem->save();
        }

        if ($request->image_image) {
            $file_path = uploadPublicFile($request->image_image, 'uploads/website-images', $productItem->image_image);
            $productItem->image_image = $file_path;
            $productItem->save();
        }

        if ($request->video_image) {
            $file_path = uploadPublicFile($request->video_image, 'uploads/website-images', $productItem->video_image);
            $productItem->video_image = $file_path;
            $productItem->save();
        }

        if ($request->audio_image) {
            $file_path = uploadPublicFile($request->audio_image, 'uploads/website-images', $productItem->audio_image);
            $productItem->audio_image = $file_path;
            $productItem->save();
        }


        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


}
