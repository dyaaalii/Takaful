<?php

namespace Modules\Subscription\Http\Controllers\Provider;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Session;
use App\Models\Language;
use App\Models\Order;
use App\Models\Setting;

class UserOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function translator(){
        $front_lang = Session::get('front_lang');
        $language = Language::where('is_default', 'Yes')->first();
        if($front_lang == ''){
            $front_lang = Session::put('front_lang', $language->lang_code);
        }
        config(['app.locale' => $front_lang]);
    }

    public function view_all_order(){
        $this->translator();

        $active_theme = 'layout2';

        $user = Auth::guard('web')->user();

        $orders = Order::with('user')->where('author_id', $user->id)->orderBy('id','desc')->get();

        return view('subscription::user.order', compact('orders','active_theme','user'));
    }


    public function view_order_detils($id){
        $this->translator();

        $active_theme = 'layout2';

        $user = Auth::guard('web')->user();

        $order = Order::with('user')->where('author_id', $user->id)->where('id',$id)->orderBy('id','desc')->first();
        $setting = Setting::first();

        return view('subscription::user.order_detils', compact('order','active_theme','user','setting'));
    }
}
