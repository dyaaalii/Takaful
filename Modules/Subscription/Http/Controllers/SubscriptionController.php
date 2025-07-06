<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BreadcrumbImage;
use App\Models\Language;
use Modules\Subscription\Entities\SubscriptionPlan;
use Session;

class SubscriptionController extends Controller
{
    public function translator(){
        $front_lang = Session::get('front_lang');
        $language = Language::where('is_default', 'Yes')->first();
        if($front_lang == ''){
            $front_lang = Session::put('front_lang', $language->lang_code);
        }
        config(['app.locale' => $front_lang]);
    }

    public function pricing_plan()
    {
        $this->translator();

        $active_theme = 'layout2';

        $plans = SubscriptionPlan::where('status', 1)->orderBy('serial','asc')->get();


        return view('subscription::pricing_plan', compact('active_theme','plans'));
    }


}
