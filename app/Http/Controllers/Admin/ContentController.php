<?php

namespace App\Http\Controllers\Admin;

use File;
use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\SeoSetting;
use App\Models\BannerImage;
use Illuminate\Http\Request;
use App\Models\SectionContent;
use App\Models\SectionControl;
use App\Models\SettingLanguage;
use App\Models\MaintainanceText;
use App\Http\Controllers\Controller;
use App\Models\SectionContentLanguage;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();
        return view('admin.maintainance_mode', compact('maintainance'));
    }

    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            'description'=> $request->maintainance_mode ? 'required' : ''
        ];
        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $maintainance = MaintainanceText::first();
        if($request->image){
            $file_path = uploadPublicFile($request->image, 'uploads/website-images', $maintainance->image);
            $maintainance->image = $file_path;
            $maintainance->save();
        }

        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function joinAsAProvider(){
        $setting = Setting::first();

        $join_as_a_provider = array(
            'image' => $setting->join_as_a_provider_banner,
            'home3_image' => $setting->home3_join_as_provider,
            'home2_image' => $setting->home2_join_as_provider,
            'title' => $setting->join_as_a_provider_title,
            'button_text' => $setting->join_as_a_provider_btn,
        );
        $join_as_a_provider = (object) $join_as_a_provider;

        $selected_theme = $setting->selected_theme;

        return view('admin.join_as_a_provider',compact('join_as_a_provider','selected_theme'));
    }



    public function subscriberSection(Request $request){
        $setting = Setting::with('settinglangadmin')->first();
        $languages = Language::get();
        $setting_language = SettingLanguage::where(['setting_id' => $setting->id, 'lang_code' => $request->lang_code])->first();


        $subscriber = array(
            'title' => $setting_language->subscriber_title,
            'description' => $setting_language->subscriber_description,
            'image' => $setting->subscriber_image,
            'background_image' => $setting->subscription_bg,
            'home2_background_image' => $setting->home2_subscription_bg,
            'home3_background_image' => $setting->home3_subscription_bg,
            'blog_page_subscription_image' => $setting->blog_page_subscription_image,
        );
        $subscriber = (object) $subscriber;

        $selected_theme = $setting->selected_theme;

        return view('admin.subscriber_section',compact('subscriber','selected_theme', 'languages'));
    }

    public function updateSubscriberSection(Request $request){
        $rules = [
            'title'=>'required',
            'description'=>'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting_language = SettingLanguage::where(['setting_id' => $setting->id, 'lang_code' => $request->lang_code])->first();

        $setting_language->subscriber_title = $request->title;
        $setting_language->subscriber_description = $request->description;
        $setting_language->save();

        // Handle background image
        if($request->background_image) {
            $setting->subscription_bg = uploadPublicFile($request->background_image, 'uploads/website-images', $setting->subscription_bg);
            $setting->save();
        }

        // Handle home2 background image
        if($request->home2_background_image) {
            $setting->home2_subscription_bg = uploadPublicFile($request->home2_background_image, 'uploads/website-images', $setting->home2_subscription_bg);
            $setting->save();
        }

        // Handle home3 background image
        if($request->background_image3) {
            $setting->home3_subscription_bg = uploadPublicFile($request->background_image3, 'uploads/website-images', $setting->home3_subscription_bg);
            $setting->save();
        }

        // Handle blog page subscription image
        if($request->blog_page_subscription_image) {
            $setting->blog_page_subscription_image = uploadPublicFile($request->blog_page_subscription_image, 'uploads/website-images', $setting->blog_page_subscription_image);
            $setting->save();
        }


        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function sectionContent(Request $request){
        $contents = SectionContent::with('contentlangadmin')->get();

        $content_id = [];
        foreach($contents as $content){
            $content_id[] = $content->id;
        }

        $languages = Language::get();

        $section_content_languages = SectionContentLanguage::whereIn('content_id', $content_id)->where('lang_code', $request->lang_code)->get();

        return view('admin.section_content',compact('contents', 'languages', 'section_content_languages'));
    }


    public function updateSectionContent(Request $request, $id){
        $rules = [
            'section_name' => $request->section_name ? 'required':'',
            'title' => 'required',
            'description' => 'required',
        ];
        $customMessages = [
            'section_name.required' => trans('admin_validation.Section name is required'),
            'title.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $section = SectionContentLanguage::find($id);

        if($request->section_name){
            $section->section_name = $request->section_name;
        }

        $section->title = $request->title;
        $section->description = $request->description;
        $section->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function sectionControl(){
        $homepage = SectionControl::get();

        return view('admin.section_control',compact('homepage'));
    }


    public function updateSectionControl(Request $request){
        foreach($request->ids as $index => $id){
            $section = SectionControl::find($id);
            $section->status = $request->status[$index];
            $section->qty = $request->quanities[$index];
            $section->save();
        }

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function seoSetup(){
        $pages = SeoSetting::all();
        return view('admin.seo_setup', compact('pages'));
    }

    public function updateSeoSetup(Request $request, $id){
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required'
        ];
        $customMessages = [
            'seo_title.required' => trans('admin_validation.Seo title is required'),
            'seo_description.required' => trans('admin_validation.Seo description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function defaultAvatar(){
        $setting = Setting::first();
        $default_avatar = $setting->default_avatar;
        return view('admin.default_profile_image', compact('default_avatar'));
    }

    public function updateDefaultAvatar(Request $request){
        $setting = Setting::first();

        if ($request->avatar) {
            $file_path = uploadPublicFile($request->avatar, 'uploads/website-images', $setting->default_avatar);
            $setting->default_avatar = $file_path;
            $setting->save();
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function defaultPlaceholder(){
        $setting = Setting::first();
        $default_placeholder = $setting->default_placeholder;
        return view('admin.default_placeholder_image', compact('default_placeholder'));
    }

    public function updateDefaultPlaceholder(Request $request){
        $setting = Setting::first();

        if ($request->placeholder) {
            $file_path = uploadPublicFile($request->placeholder, 'uploads/website-images', $setting->default_placeholder);
            $setting->default_placeholder = $file_path;
            $setting->save();
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }



}
