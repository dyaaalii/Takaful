<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Language;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Models\ScriptContent;
use App\Models\ProductComment;
use App\Models\ProductVariant;
use App\Models\ProductDiscount;
use App\Models\ProductLanguage;
use App\Http\Controllers\Controller;
use App\Models\ScriptContentLanguage;
use App\Models\ProductDiscountLanguage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){
        if($request->author_id){
            $products = Product::with('category', 'productlangadmin')->where('author_id',$request->author_id)->orderBy('id','desc')->get();
        }else{
            $products = Product::with('category', 'productlangadmin')->orderBy('id','desc')->get();
        }

        return view('admin.product', compact('products'));
    }

    public function active_product(){
        $active_products = Product::with('category', 'productlangadmin')->where('status', 1)->orderBy('id','desc')->get();
        return view('admin.active_product', compact('active_products'));

    }

    public function pending_product(){

        $pending_products = Product::with('category', 'productlangadmin')->where('status', 0)->orderBy('id','desc')->get();
        return view('admin.pending_product', compact('pending_products'));

    }

    public function topbar_offer(Request $request){
        $discount = ProductDiscount::first();
        $languages = Language::get();
        $product_discount_language = ProductDiscountLanguage::where(['discount_id' => $discount->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.product_discount', compact('discount', 'languages', 'product_discount_language'));
    }

    public function update_topbar_offer(Request $request){

        $rules = [
            'title'=>'required',
            'link'=>session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'offer'=>session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'end_time'=>session()->get('admin_lang') == $request->lang_code ? 'required':'',
        ];

        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'link.required' => trans('admin_validation.Link is required'),
            'offer.required' => trans('admin_validation.Offer is required'),
            'end_time.required' => trans('admin_validation.End time is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $discount = ProductDiscount::first();
        $product_discount_language = ProductDiscountLanguage::where(['discount_id' => $discount->id, 'lang_code' => $request->lang_code])->first();


        if($request->offer){
            $discount->offer = $request->offer;
        }

        if($request->link){
            $discount->link = $request->link;
        }

        if($request->end_time){
            $discount->end_time = $request->end_time;
        }

        if (session()->get('admin_lang') == $request->lang_code) {
            $discount->status = $request->status;
        }

        $discount->save();

        $product_discount_language->title = $request->title;
        $product_discount_language->save();

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function package_content(Request $request){

        $package_content = ScriptContent::first();
        $languages = Language::get();
        $package_content_language = ScriptContentLanguage::where(['script_id' => $package_content->id, 'lang_code' => $request->lang_code])->first();

        return view('admin.package_content', compact('package_content', 'languages', 'package_content_language'));
    }

    public function update_package_content(Request $request){
        $rules = [
            'regular_content'=>'required',
            'extend_content'=>'required',
        ];

        $customMessages = [
            'regular_content.required' => trans('admin_validation.Regular content is required'),
            'extend_content.required' => trans('admin_validation.Extend content is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $package_content = ScriptContent::first();
        $content_language = ScriptContentLanguage::where(['script_id' => $package_content->id, 'lang_code' => $request->lang_code])->first();
        $content_language->regular_content = $request->regular_content;
        $content_language->extend_content = $request->extend_content;
        $content_language->save();
        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
    public function select_product_type(){
        $productItem = ProductItem::first();
        return view('admin.select_product_type', compact('productItem'));
    }

    public function create(Request $request){
        if(!$request->product_type){
            $notification = trans('admin_validation.Something went wrong');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.select-product-type')->with($notification);
        }

        if($request->product_type == 'script'){
            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $request->product_type;

            return view('admin.create_product', compact('categories', 'authors','product_type'));

        }elseif($request->product_type == 'image'){

            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $request->product_type;

            return view('admin.create_image_product', compact('categories', 'authors','product_type'));
        }elseif($request->product_type == 'video'){

            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $request->product_type;

            return view('admin.create_image_product', compact('categories', 'authors','product_type'));
        }elseif($request->product_type == 'audio'){

            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $request->product_type;

            return view('admin.create_image_product', compact('categories', 'authors','product_type'));
        }else{
            abort(404);
        }




    }

    public function store(Request $request){
        $rules = [
            'thumb_image'=>'required',
            'upload_file' => 'required_if:upload_method,file|file|nullable|mimes:zip',
            'upload_file_link' => 'required_if:upload_method,link|nullable|url',
            'product_icon'=>'required',
            'author'=>'required',
            'category'=>'required',
            'name'=>'required',
            'slug'=>'required|unique:products',
            'preview_link'=>'required',
            'regular_price'=>'required|numeric',
            'extend_price'=>'required|numeric',
            'description'=>'required',
            'tags'=>'required',
            'status'=>'required',
            'product_type'=>'required',
        ];

        $customMessages = [
            'thumb_image.required' => trans('admin_validation.Thumbnail is required'),
            'download_file_type.required' => trans('admin_validation.Upload file type is required'),
            'product_icon.required' => trans('admin_validation.Product icon is required'),
            'upload_file.required' => trans('admin_validation.Upload file is required'),
            'upload_file_link.required' => trans('admin_validation.Upload file link is required'),
            'download_link.required' => trans('admin_validation.Download link is required'),
            'author.required' => trans('admin_validation.Author is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'preview_link.required' => trans('admin_validation.Preview link is required'),
            'regular_price.required' => trans('admin_validation.Regular price is required'),
            'extend_price.required' => trans('admin_validation.Extend price is required'),
            'extend_price.numeric' => trans('admin_validation.Extend price should be numeric value'),
            'regular_price.numeric' => trans('admin_validation.Regular price should be numeric value'),
            'description.required' => trans('admin_validation.Description is required'),
            'tags.required' => trans('admin_validation.Tag is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = new Product();


        if($request->file('upload_file')) {
            $file_path = uploadPrivateFile($request->upload_file, 'uploads/custom-images');
            $product->download_file = $file_path;
        }

        if($request->upload_file_link) {
            $product->upload_file_link = $request->upload_file_link;
        }

        if($request->thumb_image){
            $file_path = uploadPublicFile($request->thumb_image, 'uploads/custom-images');
            $product->thumbnail_image = $file_path;
        }


        if($request->product_icon){
            $file_path = uploadPublicFile($request->product_icon, 'uploads/custom-images');
            $product->product_icon = $file_path;
        }

        $product->product_type = $request->product_type;
        $product->author_id = $request->author;

        $product->slug = $request->slug;
        $product->category_id = $request->category;
        $product->preview_link = $request->preview_link;
        $product->regular_price = $request->regular_price;
        $product->extend_price = $request->extend_price;
        $product->status = $request->status;
        $product->popular_item = $request->popular_item ? 1 : 0;
        $product->trending_item = $request->trending_item ? 1 : 0;
        $product->featured_item = $request->featured_item ? 1 : 0;
        $product->high_resolution = $request->high_resolution ? 1 : 0;
        $product->cross_browser = $request->cross_browser ? 1 : 0;
        $product->documentation = $request->documentation ? 1 : 0;
        $product->layout = $request->layout ? 1 : 0;
        $product->save();

        $languages = Language::get();
        foreach($languages as $language){
            $product_language = new ProductLanguage();
            $product_language->product_id = $product->id;
            $product_language->lang_code = $language->lang_code;
            $product_language->name = $request->name;
            $product_language->description = $request->description;
            $product_language->tags = $request->tags;
            $product_language->seo_title = $request->seo_title ? $request->seo_title : $request->name;
            $product_language->seo_description = $request->seo_description ? $request->seo_description : $request->name;
            $product_language->save();
        }


        $notification = trans('admin_validation.Created successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function store_image_type_product(Request $request){
        $rules = [
            'thumb_image'=>'required',
            'product_icon'=>'required',
            'author'=>'required',
            'category'=>'required',
            'name'=>'required',
            'slug'=>'required|unique:products',
            'preview_link'=>'required',
            'regular_price'=>'required',
            'description'=>'required',
            'tags'=>'required',
            'status'=>'required',
            'product_type'=>'required',
        ];

        $customMessages = [
            'thumb_image.required' => trans('admin_validation.Thumbnail is required'),
            'product_icon.required' => trans('admin_validation.Product icon is required'),
            'author.required' => trans('admin_validation.Author is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'preview_link.required' => trans('admin_validation.Preview link is required'),
            'regular_price.required' => trans('admin_validation.Regular price is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'tags.required' => trans('admin_validation.Tag is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = new Product();

        if($request->thumb_image){
            $file_path = uploadPublicFile($request->thumb_image, 'uploads/custom-images');
            $product->thumbnail_image = $file_path;
        }

        if($request->product_icon){
            $file_path = uploadPublicFile($request->product_icon, 'uploads/custom-images');
            $product->product_icon = $file_path;
        }


        $product->product_type = $request->product_type;
        $product->author_id = $request->author;
        $product->slug = $request->slug;
        $product->preview_link = $request->preview_link;
        $product->regular_price = $request->regular_price;
        $product->category_id = $request->category;
        $product->preview_link = $request->preview_link;
        $product->status = $request->status;
        $product->popular_item = $request->popular_item ? 1 : 0;
        $product->trending_item = $request->trending_item ? 1 : 0;
        $product->featured_item = $request->featured_item ? 1 : 0;
        $product->high_resolution = $request->high_resolution ? 1 : 0;
        $product->cross_browser = $request->cross_browser ? 1 : 0;
        $product->documentation = $request->documentation ? 1 : 0;
        $product->layout = $request->layout ? 1 : 0;
        $product->save();

        $languages = Language::get();
        foreach($languages as $language){
            $product_language = new ProductLanguage();
            $product_language->product_id = $product->id;
            $product_language->lang_code = $language->lang_code;
            $product_language->name = $request->name;
            $product_language->description = $request->description;
            $product_language->tags = $request->tags;
            $product_language->seo_title = $request->seo_title ? $request->seo_title : $request->name;
            $product_language->seo_description = $request->seo_description ? $request->seo_description : $request->name;
            $product_language->save();
        }

        $notification = trans('admin_validation.Created successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-variant', $product->id)->with($notification);
    }

    public function edit(Request $request,$id){

        $product = Product::find($id);
        $product_language = ProductLanguage::where(['product_id' => $id, 'lang_code' => $request->lang_code])->first();
        $languages = Language::get();
        if($product->product_type == 'script'){
            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;

            return view('admin.edit_product', compact('categories', 'authors','product_type','product','languages','product_language'));

        }elseif($product->product_type == 'image'){
            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;

            return view('admin.edit_image_product', compact('categories', 'authors','product_type','product','languages','product_language'));

        }elseif($product->product_type == 'video'){
            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;

            return view('admin.edit_image_product', compact('categories', 'authors','product_type','product','languages','product_language'));

        }elseif($product->product_type == 'audio'){
            $categories = Category::with('catlangadmin')->where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;

            return view('admin.edit_image_product', compact('categories', 'authors','product_type','product','languages','product_language'));
        }
    }

    public function update(Request $request, $id){

        $rules = [
            'author'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'category'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'name'=>'required',
            'preview_link'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'regular_price'=> session()->get('admin_lang') == $request->lang_code ? 'required|numeric':'',
            'extend_price'=> session()->get('admin_lang') == $request->lang_code ? 'required|numeric':'',
            'description'=>'required',
            'tags'=>'required',
            'status'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'product_type'=> session()->get('admin_lang') == $request->lang_code ?'required':'',
        ];

        $customMessages = [
            'download_file_type.required' => trans('admin_validation.Upload file type is required'),
            'upload_file.required' => trans('admin_validation.Upload file is required'),
            'download_link.required' => trans('admin_validation.Download link is required'),
            'author.required' => trans('admin_validation.Author is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'regular_price.required' => trans('admin_validation.Regular price is required'),
            'extend_price.required' => trans('admin_validation.Extend price is required'),
            'extend_price.numeric' => trans('admin_validation.Extend price should be numeric value'),
            'regular_price.numeric' => trans('admin_validation.Regular price should be numeric value'),
            'description.required' => trans('admin_validation.Description is required'),
            'tags.required' => trans('admin_validation.Tag is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = Product::find($id);
        $product_language = ProductLanguage::where(['product_id' => $id, 'lang_code' => $request->lang_code])->first();

        if(session()->get('admin_lang') == $request->lang_code){
            if($request->thumb_image){

                $file_path = uploadPublicFile($request->thumb_image, 'uploads/custom-images', $product->thumbnail_image);

                $product->thumbnail_image = $file_path;
                $product->save();
            }


            if($request->product_icon){

                $file_path = uploadPublicFile($request->product_icon, 'uploads/custom-images', $product->product_icon);

                $product->product_icon = $file_path;
                $product->save();
            }

            if($request->file('upload_file')) {

                $file_path = uploadPrivateFile($request->upload_file, 'uploads/custom-images', $product->download_file);

                $product->download_file = $file_path;
                $product->save();

            }

            if($request->upload_file_link) {
                $product->upload_file_link = $request->upload_file_link;
                $product->save();
            }

            $product->product_type = $request->product_type;
            $product->author_id = $request->author;
            $product->category_id = $request->category;
            $product->preview_link = $request->preview_link;
            $product->regular_price = $request->regular_price;
            $product->extend_price = $request->extend_price;
            $product->status = $request->status;
            $product->popular_item = $request->popular_item ? 1 : 0;
            $product->trending_item = $request->trending_item ? 1 : 0;
            $product->featured_item = $request->featured_item ? 1 : 0;
            $product->high_resolution = $request->high_resolution ? 1 : 0;
            $product->cross_browser = $request->cross_browser ? 1 : 0;
            $product->documentation = $request->documentation ? 1 : 0;
            $product->layout = $request->layout ? 1 : 0;
            $product->save();
        }

        $product_language->name = $request->name;
        $product_language->description = $request->description;
        $product_language->tags = $request->tags;
        $product_language->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product_language->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product_language->save();

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function image_product_update(Request $request, $id){
        $rules = [
            'author'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'category'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'name'=>'required',
            'preview_link'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'regular_price'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'description'=>'required',
            'tags'=>'required',
            'status'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
            'product_type'=> session()->get('admin_lang') == $request->lang_code ? 'required':'',
        ];

        $customMessages = [
            'author.required' => trans('admin_validation.Author is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'preview_link.required' => trans('admin_validation.Preview link is required'),
            'regular_price.required' => trans('admin_validation.Regular price is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'tags.required' => trans('admin_validation.Tag is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = Product::find($id);
        $product_language = ProductLanguage::where(['product_id' => $id, 'lang_code' => $request->lang_code])->first();

        if(session()->get('admin_lang') == $request->lang_code){

            if($request->thumb_image){

                $file_path = uploadPublicFile($request->thumb_image, 'uploads/custom-images', $product->thumbnail_image);

                $product->thumbnail_image = $file_path;
                $product->save();
            }


            if($request->product_icon){

                $file_path = uploadPublicFile($request->product_icon, 'uploads/custom-images', $product->product_icon);

                $product->product_icon = $file_path;
                $product->save();
            }


            $product->author_id = $request->author;
            $product->preview_link = $request->preview_link;
            $product->regular_price = $request->regular_price;
            $product->category_id = $request->category;
            $product->status = $request->status;
            $product->popular_item = $request->popular_item ? 1 : 0;
            $product->trending_item = $request->trending_item ? 1 : 0;
            $product->featured_item = $request->featured_item ? 1 : 0;
            $product->high_resolution = $request->high_resolution ? 1 : 0;
            $product->cross_browser = $request->cross_browser ? 1 : 0;
            $product->documentation = $request->documentation ? 1 : 0;
            $product->layout = $request->layout ? 1 : 0;
            $product->save();
        }

        $product_language->name = $request->name;
        $product_language->description = $request->description;
        $product_language->tags = $request->tags;
        $product_language->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product_language->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product_language->save();

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function product_variant($id){
        $product = Product::find($id);

        if($product->product_type == 'image'){
            $categories = Category::where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;
            $product_variants = ProductVariant::where('product_id', $id)->get();
            $setting = Setting::first();

            return view('admin.product_variant', compact('categories', 'authors','product_type','product','product_variants','setting'));
        }elseif($product->product_type == 'video'){

            $categories = Category::where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;
            $product_variants = ProductVariant::where('product_id', $id)->get();
            $setting = Setting::first();

            return view('admin.product_variant', compact('categories', 'authors','product_type','product','product_variants','setting'));

        }elseif($product->product_type == 'audio'){

            $categories = Category::where('status', 1)->get();
            $authors = User::where('status', 1)->orderBy('name', 'asc')->get();
            $product_type = $product->product_type;
            $product_variants = ProductVariant::where('product_id', $id)->get();
            $setting = Setting::first();

            return view('admin.product_variant', compact('categories', 'authors','product_type','product','product_variants','setting'));

        }else{
            abort(404);
        }
    }

    public function store_product_variant(Request $request, $id){
        $rules = [
            'variant_name'=>'required',
            'file_name' => 'required_if:upload_method,file|file|nullable|mimes:zip',
            'upload_file_link' => 'required_if:upload_method,link|nullable|url',
            'price'=>'required|numeric',
        ];

        $customMessages = [
            'variant_name.required' => trans('admin_validation.Variant name is required'),
            'file_name.required' => trans('admin_validation.Upload file is required'),
            'upload_file_link.required' => trans('admin_validation.Upload_file_link is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'price.numeric' => trans('admin_validation.Price should be numeric value'),
        ];
        $this->validate($request, $rules,$customMessages);

        $variant = new ProductVariant();

        if($request->file('file_name')) {
            $file_path = uploadPrivateFile($request->file_name, 'uploads/custom-images');
            $variant->file_name = $file_path;
        }

        if($request->upload_file_link) {
            $variant->upload_file_link = $request->upload_file_link;
        }

        $variant->variant_name = $request->variant_name;
        $variant->price = $request->price;
        $variant->product_id = $id;
        $variant->save();

        $notification = trans('admin_validation.Created successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function update_product_variant(Request $request, $id){
        $rules = [
            'variant_name'=>'required',
            'price'=>'required|numeric',
        ];

        $customMessages = [
            'variant_name.required' => trans('admin_validation.Variant name is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'price.numeric' => trans('admin_validation.Price should be numeric value'),
        ];
        $this->validate($request, $rules,$customMessages);

        $variant = ProductVariant::find($id);

        if($request->file('file_name')) {
            $file_path = uploadPrivateFile($request->file_name, 'uploads/custom-images', $variant->file_name);
            $variant->file_name = $file_path;
            $variant->save();
        }

        if($request->upload_file_link) {
            $variant->upload_file_link = $request->upload_file_link;
        }

        $variant->variant_name = $request->variant_name;
        $variant->price = $request->price;
        $variant->save();

        $notification = trans('admin_validation.Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function delete_product_variant($id){
        $order_item = OrderItem::where('variant_id', $id)->first();

        if (!$order_item) {
            $variant = ProductVariant::find($id);
            $old_download_file = $variant->file_name;
            $variant->delete();

            deleteFile($old_download_file);

            $notification = trans('admin_validation.Deleted successfully');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{
            $notification = trans("You can't delete sold product variant");
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function download_existing_file($file_name){
        $filepath= public_path() . "/uploads/custom-images/".$file_name;
        return response()->download($filepath);
    }


    public function personal_download_script($id){

        $product = Product::findOrFail($id);

        $file_extension = pathinfo($product->download_file, PATHINFO_EXTENSION);

        $custom_filename = $product->productlangfrontend->name . '-' . date('Y-m-d') .'.'. $file_extension;

        return downloadPrivateFile($product->download_file, $custom_filename);

    }


    public function personal_download_variant($id){
        $product_variant = ProductVariant::findOrFail($id);

        $product = Product::findOrFail($product_variant->product_id);

        $file_extension = pathinfo($product_variant->file_name, PATHINFO_EXTENSION);

        $custom_filename = $product->productlangfrontend->name . '-' . date('Y-m-d') .'.'. $file_extension;

        return downloadPrivateFile($product_variant->file_name, $custom_filename);

    }


    public function destroy($id){

        $product = Product::findOrFail($id);

        deleteFile($product->thumbnail_image);
        deleteFile($product->product_icon);
        deleteFile($product->download_file);


        if($product->product_type!='script'){
            $variants = ProductVariant::where('product_id', $id)->get();
            foreach($variants as $variant){
                $variant->delete();
                deleteFile($old_download_file);
            }
        }

        $product_language = ProductLanguage::where('product_id', $id)->delete();
        $product_comment = ProductComment::where('product_id', $id)->delete();
        $product_review = Review::where('product_id', $id)->delete();
        $wishlist = Wishlist::where('product_id', $id)->delete();

        $product->delete();

        $notification = trans('admin_validation.Deleted successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
