<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    @yield('title')
    @php
        $setting = App\Models\Setting::with('settinglangfrontend')->first();

        $front_lang = Session::get('front_lang');
        $language = App\Models\Language::where('is_default', 'Yes')->first();
        if($front_lang == ''){
            $front_lang = Session::put('front_lang', $language->lang_code);
        }

        $lang_direction = App\Models\Language::where('lang_code', $front_lang)->first();

        $currency_code = Session::get('currency_code');
        $currency_icon = Session::get('currency_icon');
        $currency_rate = Session::get('currency_rate');
        $currency_position = Session::get('currency_position');

        $default_currency = App\Models\MultiCurrency::where('is_default', 'Yes')->first();

        if($currency_code == ''){
            $currency_code = Session::put('currency_code', $default_currency->currency_code);
        }

        if($currency_icon == ''){
            $currency_icon = Session::put('currency_icon', $default_currency->currency_icon);
        }

        if($currency_rate == ''){
            $currency_rate = Session::put('currency_rate', $default_currency->currency_rate);
        }

        if($currency_position == ''){
            $currency_position = Session::put('currency_position', $default_currency->currency_position);
        }

    @endphp
    <link rel="icon" type="image/png" href="{{ custom_asset($setting->favicon) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/price_range_style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/price_range_ui.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/summernote.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/pricing-two.css') }}">


    <link rel="stylesheet" href="{{ asset('frontend/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
    @if ($lang_direction->lang_direction=='right_to_left')
    <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}">
    @endif

    <style>
        .tox .tox-promotion,
        .tox-statusbar__branding{
            display: none !important;
        }
    </style>
</head>

<body class="home_3">

    <!--=============================
        MENU START
    ==============================-->
    <nav class="navbar navbar-expand-lg main_menu main_menu_3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ custom_asset($setting->logo_three) }}" alt="Alasmart" class="img-fluid w-100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="far fa-bars menu_icon"></i>
                <i class="far fa-times close_icon"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav m-auto">
                    <li class="nav-item">
                        @php
                            if($setting->selected_theme==1){
                               $route = route('home',['theme' => 1]);
                            }else if($setting->selected_theme==2){
                                $route = route('home',['theme' => 2]);
                            }else if($setting->selected_theme==3){
                                $route = route('home',['theme' => 3]);
                            }else if($setting->selected_theme==0){
                                $route = route('home',['theme' => 1]);
                            }
                        @endphp
                        <a class="nav-link {{ Route::is('home') ? 'active':'' }}" href="{{ $route }}">{{__('user.Home')}}
                            @if ($setting->selected_theme==0)
                            <i class="far fa-chevron-down"></i>
                            @endif
                        </a>
                        @if ($setting->selected_theme==0)
                        <ul class="wsus__droap_menu">
                            <li><a href="{{ route('home',['theme' => 1]) }}">{{__('user.home one')}}</a></li>
                            <li><a href="{{ route('home',['theme' => 2]) }}">{{__('user.home two')}}</a></li>
                            <li><a class="active" href="{{ route('home',['theme' => 3]) }}">{{__('user.home three')}}</a></li>
                        </ul>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">{{__('user.Products')}}</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="javascript:;">{{__('user.Pages')}} <i class="far fa-chevron-down"></i></a>
                        <ul class="wsus__droap_menu">
                            @if ($setting->commission_type == 'subscription')
                                    @php
                                        $json_module_data = file_get_contents(base_path('modules_statuses.json'));
                                        $module_status = json_decode($json_module_data);
                                    @endphp

                                    @if ($module_status->Subscription)

                                        <li>
                                            <a class="{{ Route::is('pricing-plan') ? 'active':'' }}" href="{{ route('pricing-plan') }}">{{__('user.Pricing Plan')}}</a>
                                        </li>
                                    @endif
                            @endif
                            <li><a class="{{ Route::is('become-author-page') ? 'active':'' }}" href="{{ route('become-author-page') }}">{{__('user.become an author')}}</a></li>

                            @if ($setting->blog_left_right == 0)
                            <li><a class="{{ request()->get('blog') == 'leftbar' ? 'active':'' }}" href="{{ route('blogs', ['blog'=>'leftbar']) }}">{{__('user.blog leftbar')}}</a></li>
                            <li><a class="{{ request()->get('blog') == 'rightbar' ? 'active':'' }}" href="{{ route('blogs', ['blog'=>'rightbar']) }}">{{__('user.blog rightbar')}}</a></li>
                            @endif

                            <li><a class="{{ Route::is('faq') ? 'active':'' }}" href="{{ route('faq') }}">{{__('user.FAQ')}}</a></li>
                            <li><a class="{{ Route::is('privacy-policy') ? 'active':'' }}" href="{{ route('privacy-policy') }}">{{__('user.privacy policy')}}</a></li>
                            <li><a class="{{ Route::is('terms-and-conditions') ? 'active':'' }}" href="{{ route('terms-and-conditions') }}">{{__('user.terms and condition')}}</a></li>

                            @php
                                $pages = App\Models\CustomPage::where('status', 1)->get();
                            @endphp
                            @foreach ($pages as $page)
                            <li><a href="{{ route('custom-page', $page->slug) }}">{{ $page->customlangfrontend->page_name }}</a></li>
                            @endforeach

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs') }}">{{__('user.Blog')}}</a>
                    </li> --}}

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('about-us') ? 'active':'' }}" href="{{ route('about-us') }}">{{__('user.About Us')}}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact-us') }}">{{__('user.Contact')}}</a>
                    </li>
                </ul>

                <ul class="right_menu d-flex flex-wrap">
                    <li>
                        <a href="{{ route('cart-view') }}">
                            <img src="{{ asset('frontend/images/menu_cart_icom.png') }}" alt="user" class="img-fluid w-100">
                            <span id="cartQty">0</span>
                        </a>
                    </li>
                    <li><a class="start_btn" href="{{ route('select-product-type') }}">{{__('user.Start Selling')}}</a></li>
                    <li><a class="support_btn" href="{{ route('dashboard') }}">{{__('user.Dashboard')}}</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!--=============================
        MENU END
    ==============================-->

    @yield('frontend-content')

    <!--=============================
        SUBSCRIBE 3 START
    ==============================-->
    <section class="wsus__subscribe wsus__subscribe_3 mt_120 xs_mt_30">
        <div class="wsus__subscribe_3_bg" style="background: url({{ custom_asset($setting->home3_subscription_bg) }});">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-6 col-sm-10 col-md-8 col-xl-6">
                        <div class="wsus__subscribe_text">
                            @php
                                $setting = App\Models\Setting::with('settinglangfrontend')->first();
                            @endphp
                            <h2>{{ $setting->settinglangfrontend->subscriber_title }}</h2>
                            <p>{{ $setting->settinglangfrontend->subscriber_description }}</p>
                            <form id="fsubscriberForm">
                                @csrf
                                <input type="text" name="email" placeholder="{{__('user.Enter your email address')}}">
                                <button class="common_btn" id="fsubSubmitBtn" type="submit">{{__('user.Subscribe')}}</button>
                                <button class="common_btn d-none" id="fsubShowSpain" type="submit"><i class="fas fa-spinner fa-spin"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        SUBSCRIBE 3 END
    ==============================-->


    @php
        $footer = App\Models\Footer::first();
        $item_sold = App\Models\OrderItem::get()->count();
        $total_earning = App\Models\OrderItem::get()->sum('price');
        $total_user = App\Models\User::where(['email_verified' => 1, 'status' => 1])->get()->count();
        $social_links=App\Models\FooterSocialLink::get();
    @endphp

    <!--=============================
        FOOTER START
    ==============================-->
    <footer class="pt_120 xs_pt_80" style="background: url({{ asset('frontend/images/footer_bg.jpg') }});">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-md-4 col-lg-4">
                    <div class="wsus__footer_content">
                        <a class="footer_logo" href="{{ route('home') }}">
                            <img src="{{ custom_asset($setting->footer_logo) }}" alt="Alsmart" class="img-fluid w-100">
                        </a>
                        <p class="description">{{ $footer->footerlangfrontend->description }}</p>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-lg-2">
                    <div class="wsus__footer_content">
                        <h4>{{__('user.Support')}}</h4>
                        <ul>
                            <li><a href="{{ route('contact-us') }}">{{__('user.Contact Us')}}</a></li>
                            <li><a href="{{ route('blogs') }}">{{__('user.Our Blog')}}</a></li>
                            <li><a href="{{ route('faq') }}">{{__('user.FAQ')}}</a></li>
                            <li><a href="{{ route('privacy-policy') }}">{{__('user.Privacy Policy')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-lg-2">
                    <div class="wsus__footer_content">
                        <h4>{{__('user.Quick Link')}}</h4>
                        <ul>
                            <li><a href="{{ route('dashboard') }}">{{__('user.My Profile')}}</a></li>
                            <li><a href="{{ route('about-us') }}">{{__('user.About Us')}}</a></li>
                            <li><a href="{{ route('login') }}">{{__('user.Login')}}</a></li>
                            <li><a href="{{ route('register') }}">{{__('user.Registration')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-lg-3">
                    <div class="wsus__footer_content">
                        <h4>{{__('user.Important Link')}}</h4>
                        <ul>
                            @if ($setting->commission_type == 'subscription')
                                @php
                                    $json_module_data = file_get_contents(base_path('modules_statuses.json'));
                                    $module_status = json_decode($json_module_data);
                                @endphp

                                @if ($module_status->Subscription)

                                    <li>
                                        <a href="{{ route('pricing-plan') }}">{{__('user.Pricing Plan')}}</a>
                                    </li>
                                @endif
                            @endif
                            <li><a href="{{ route('register') }}">{{__('user.Become an author')}}</a></li>
                            <li><a href="{{ route('terms-and-conditions') }}">{{__('user.Terms & Conditions')}}</a></li>
                            <li><a href="{{ route('products') }}">{{__('user.Our product')}}</a></li>
                            <li><a href="{{ route('cart-view') }}">{{__('user.Cart page')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="wsus__footer_link mt_115 xs_mt_25">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <ul class="social_link d-flex flex-wrap">
                            @foreach ($social_links as $link)
                            <li><a href="{{ $link->link }}"><i class="{{ $link->icon }}"></i></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="footer_counter d-flex flex-wrap">
                            <li>
                                <p>{{__('user.Active Customers')}}</p>
                                <h3 class="counter">{{ $total_user }}</h3>
                            </li>
                            <li>
                                <p>{{__('user.Total Sold Item')}}</p>
                                <h3 class="counter">{{ $item_sold }}</h3>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="wsus__footer_bottom">
            <div class="container max-width">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__footer_copyright d-flex flex-wrap">
                            <p>{{ $footer->footerlangfrontend->copyright }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="wsus__footer_payment d-flex flex-wrap">
                            <div class="img">
                                <img src="{{ custom_asset($footer->payment_image) }}" alt="payment gateway" class="img-fluid w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--=============================
        FOOTER END
    ==============================-->


    <!--============================
        SCROLL BUTTON START
    =============================-->
    <div class="wsus__scroll_btn">
        <p>{{__('user.Up to Top')}}</p>
        <span><i class="far fa-angle-up"></i></span>
    </div>
    <!--============================
        SCROLL BUTTON END
    =============================-->


    <!--jquery library js-->
    <script src="{{ asset('frontend/js/jquery-3.7.1.min.js') }}"></script>
    <!--bootstrap js-->
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('frontend/js/Font-Awesome.js') }}"></script>
    <!--simplyCountdown js-->
    <script src="{{ asset('frontend/js/simplyCountdown.js') }}"></script>
    <!--countup js-->
    <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.countup.min.js') }}"></script>
    <!--slick js-->
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <!--nice select js-->
    <script src="{{ asset('frontend/js/jquery.nice-select.min.js') }}"></script>
    <!--wow js-->
    <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
    <!--price range js-->
    <script src="{{ asset('frontend/js/price_range_script.js') }}"></script>
    <script src="{{ asset('frontend/js/price_range_ui.min.js') }}"></script>
    <!--isotope js-->
    <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
    <!--summernote js-->
    <script src="{{ asset('frontend/js/summernote.min.js') }}"></script>

    <!--main/custom js-->
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <script src="{{ asset('toastr/toastr.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        @if(Session::has('messege'))
        var type="{{Session::get('alert-type','info')}}"
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('messege') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('messege') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('messege') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('messege') }}");
                break;
        }
        @endif
    </script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif

    @stack('frontend_js')

    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {
                $("#fsubscriberForm").on('submit', function(e){
                    e.preventDefault();
                    $('#fsubShowSpain').removeClass('d-none');
                    $('#fsubSubmitBtn').addClass('d-none');
                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 'DEMO'){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    let loading = "{{__('user.Processing...')}}"

                    $("#fsubscribe_btn").html(loading);
                    $("#fsubscribe_btn").attr('disabled',true);

                    $.ajax({
                        type: 'POST',
                        data: $('#fsubscriberForm').serialize(),
                        url: "{{ route('subscribe-request') }}",
                        success: function (response) {
                            if(response.status == 1){
                                toastr.success(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#fsubscribe_btn").html(subscribe);
                                $("#fsubscribe_btn").attr('disabled',false);
                                $("#fsubscriberForm").trigger("reset");
                                $('#fsubShowSpain').addClass('d-none');
                                $('#fsubSubmitBtn').removeClass('d-none');
                            }

                            if(response.status == 0){
                                toastr.error(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#fsubscribe_btn").html(subscribe);
                                $("#fsubscribe_btn").attr('disabled',false);
                                $("#fsubscriberForm").trigger("reset");
                                $('#fsubShowSpain').addClass('d-none');
                                $('#fsubSubmitBtn').removeClass('d-none');
                            }
                        },
                        error: function(err) {
                            $('#fsubShowSpain').addClass('d-none');
                            $('#fsubSubmitBtn').removeClass('d-none');
                            toastr.error('Something went wrong');
                            let subscribe = "{{__('user.Subscribe')}}"
                            $("#fsubscribe_btn").html(subscribe);
                            $("#fsubscribe_btn").attr('disabled',false);
                            $("#fsubscriberForm").trigger("reset");
                        }
                    });
                });


                $("#footerTopSubscriberForm").on('submit', function(e){
                    e.preventDefault();
                    $('#footerTopSubShowSpain').removeClass('d-none');
                    $('#footerTopSubSubmitBtn').addClass('d-none');
                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 'DEMO'){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    let loading = "{{__('user.Processing...')}}"

                    $("#fsubscribe_btn").html(loading);
                    $("#fsubscribe_btn").attr('disabled',true);

                    $.ajax({
                        type: 'POST',
                        data: $('#footerTopSubscriberForm').serialize(),
                        url: "{{ route('subscribe-request') }}",
                        success: function (response) {
                            if(response.status == 1){
                                toastr.success(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#fsubscribe_btn").html(subscribe);
                                $("#fsubscribe_btn").attr('disabled',false);
                                $("#footerTopSubscriberForm").trigger("reset");
                                $('#footerTopSubShowSpain').addClass('d-none');
                                $('#footerTopSubSubmitBtn').removeClass('d-none');
                            }

                            if(response.status == 0){
                                toastr.error(response.message);
                                let subscribe = "{{__('user.Subscribe')}}"
                                $("#fsubscribe_btn").html(subscribe);
                                $("#fsubscribe_btn").attr('disabled',false);
                                $("#footerTopSubscriberForm").trigger("reset");
                                $('#footerTopSubShowSpain').addClass('d-none');
                                $('#footerTopSubSubmitBtn').removeClass('d-none');
                            }
                        },
                        error: function(err) {
                            $('#footerTopSubShowSpain').addClass('d-none');
                            $('#footerTopSubSubmitBtn').removeClass('d-none');
                            toastr.error('Something went wrong');
                            let subscribe = "{{__('user.Subscribe')}}"
                            $("#fsubscribe_btn").html(subscribe);
                            $("#fsubscribe_btn").attr('disabled',false);
                            $("#footerTopSubscriberForm").trigger("reset");
                        }
                    });
                });

                $("#country_id").on("change",function(){
                    var countryId = $("#country_id").val();
                    if(countryId){
                        $.ajax({
                            type:"get",
                            url:"{{url('/state-by-country/')}}"+"/"+countryId,
                            success:function(response){
                                $("#state_id").html(response.states);
                            },
                            error:function(err){

                            }
                        })
                    }else{
                        var response= "<option value=''>{{__('user.Select a State')}}</option>";
                        $("#state_id").html(response);
                    }

                });

                $("#state_id").on("change",function(){
                    var stateId = $("#state_id").val();
                    if(stateId){
                        $.ajax({
                            type:"get",
                            url:"{{url('/city-by-state/')}}"+"/"+stateId,
                            success:function(response){
                                $("#city_id").html(response.cities);
                            },
                            error:function(err){

                            }
                        })
                    }else{
                        var response= "<option value=''>{{__('user.Select a city')}}</option>";
                        $("#state_id").html(response);
                    }

                });

                $('.select2').select2();
                tinymce.init({
                    selector: '#editor',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                    ]
                });
                $('.tags').tagify();
            });
        })(jQuery);

    </script>

    <script>
        "use strict";
            //wishlist start
            function addWishlist(product_id){
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:"POST",
                    url:"{{ url('/add/wishlist/') }}/"+product_id,
                    dataType:'json',
                    success:function(response){
                        if(response.success){
                            toastr.success(response.success);
                        }else{
                            toastr.error(response.error);
                        }
                    }
                })
            };
        //wishlist end
        //cart start
        function addToCard(product_id){
            let product_type=$('#product_type').val();
            let regular_price = $('#script_regular_price').val();
            let extend_price = $('#script_extend_price').val();
            let price = $('#image_price').val();
            let variant_id = $('#variant_id option:selected').val();
            let variant_name = $('#variant_id option:selected').text();
            let price_type = $('#price_type option:selected').val();
            let product_name = $('#product_name').val();
            let slug = $('#slug').val();
            let category_name = $('#category_name').val();
            let category_id = $('#category_id').val();
            let product_image = $('#product_image').val();
            let author_name = $('#author_name').val();
            let author_id = $('#author_id').val();
            $.ajax({
                headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                type:"POST",
                dataType:"json",
                data:{
                    product_type:product_type,
                    regular_price:regular_price,
                    extend_price:extend_price,
                    price:price,
                    variant_id:variant_id,
                    variant_name:variant_name,
                    price_type:price_type,
                    product_name:product_name,
                    slug:slug,
                    category_name:category_name,
                    category_id:category_id,
                    product_image:product_image,
                    author_name:author_name,
                    author_id:author_id,
                },
                url: "{{ url('/add-to-cart') }}" + "/" + product_id,
                success:function(response){
                    miniCart();
                    if(response.status == 1){
                        toastr.success(response.message);
                    }
                    if(response.status == 0){
                        toastr.error(response.message);
                    }
                }
            });
        };
    //add to cart function end
    //mini cart function start
        function miniCart(){
            $.ajax({
                type:"GET",
                dataType:"json",
                url: "{{ url('/mini-cart') }}",
                success:function(response){
                    $('#cartQty').text(response.cartQty);
                }
            });
        }
        miniCart();
        //mini cart function end

        //cart item  function start
        function cartItem(){
            $.ajax({
                type:"GET",
                dataType:"json",
                url: "{{ url('/cart-item') }}",
                success:function(response){
                    let cartItem="";
                    $('#cartTotal').text(response.cartTotal);
                    $.each(response.carts, function(key, value){
                        cartItem+=`<tr>
                                    <td class="img">
                                        <a href="{{ url('/product/${value.options.slug}') }}">
                                            <img src="${ value.options.image }" alt="cart item"
                                                class="img-fluid w-100">
                                        </a>
                                    </td>
                                    <td class="description">
                                        <h3><a href="{{ url('/product/${value.options.slug}') }}">${value.name}</a></h3>
                                        <p>
                                            <span>{{__('user.Item by')}}</span> ${value.options.author}
                                            <b class="${value.options.variant_name!=null?'':'d-none'}">${value.options.variant_name!=null?value.options.variant_name:''}</b>
                                            <b class="${value.options.price_type!=null?'':'d-none'}">${value.options.price_type!=null?value.options.price_type:''}</b>
                                        </p>

                                    </td>
                                    <td class="price">
                                        <p>${response.setting.currency_icon+value.price}</p>
                                    </td>
                                    <td class="discount">
                                        <p>${value.options.category}</p>
                                    </td>
                                    <td class="action">
                                        <a href="javascript:;" id="${value.rowId}" onclick="cartRemove(this.id)"><i class="far fa-times"></i></a>
                                    </td>
                            </tr>`;
                    });
                    $('#cartItem').html(cartItem);
                }
            });
        }
        cartItem();

        function cartRemove(rowId){
            $.ajax({
                type:"GET",
                dataType:"json",
                url: "{{ url('/cart-remove') }}"+ "/" + rowId,
                success:function(response){
                    miniCart();
                    cartItem();
                    couponCalculation();
                    if(response.status == 1){
                        toastr.success(response.message);
                    }
                }
            });
        };
        //cart item function end
        //coupon start
        function couponApply(){
            let coupon_name=$('#coupon_name').val();
            if(coupon_name){
                $.ajax({
                    headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    type:"POST",
                    dataType:"json",
                    data:{
                        coupon_name:coupon_name,
                    },
                    url: "{{ url('/coupon-apply') }}",
                    success:function(response){
                        if(response.status == 1){
                            $('#coupon_name').val('');
                            couponCalculation();
                            toastr.success(response.message);
                        }
                        if(response.status == 0){
                            $('#coupon_name').val('');
                            toastr.error(response.message);
                        }
                    }
                });
            }else{
                let coupon_valid = $('#coupon_valid').val();
                toastr.error(coupon_valid);
            }
        };

        function couponCalculation(){
            $.ajax({
               type:"GET",
               url: "{{ url('/coupon-calculation') }}",
               dataType:'json',
               success:function(data){
                if(data.total){
                    $('#calprice').html(`
                        <p class="subtotal">{{__('user.subtotal')}} <span>${data.setting.currency_icon}<span id="cartTotal">${data.total}</span></span></p>
                        <p class="discount">{{__('user.Discount')}} <span>(-)${data.setting.currency_icon} 0</span></p>
                        <p class="total">{{__('user.Total')}} <span><span>${data.setting.currency_icon}<span>${data.total}</span></span></p>
                        <a class="common_btn" href="{{ route('checkout') }}">{{__('user.Proceed to Checkout')}}</a>
                    `);
                }else{
                    $('#calprice').html(`
                        <p class="subtotal">{{__('user.subtotal')}} <span>${data.setting.currency_icon}<span id="cartTotal">${data.sub_total}</span></span></p>
                        <p class="subtotal">{{__('user.coupon')}} <span>${data.coupon_name} <button type="submit" class="btn btn-danger btn-sm" onclick="couponRemove()"><i class="fa fa-times"></i></button></span></p>
                        <p class="discount">{{__('user.Discount')}} <span>(-)${data.setting.currency_icon} ${data.discount_amount}</span></p>
                        <p class="total">{{__('user.Total')}} <span><span>${data.setting.currency_icon}</span>${data.total_amount}</span></p>
                        <a class="common_btn" href="{{ route('checkout') }}">{{__('user.Proceed to Checkout')}}</a>
                    `);
                }
            }
         });
        };
        couponCalculation();
        function couponRemove(){
            $.ajax({
               type:"GET",
               url: "{{ url('/coupon-remove') }}",
               dataType:'json',
               success:function(response){
                 $('#coupon_name').val('');
                couponCalculation();
                if(response.status == 1){
                    toastr.success(response.message);
                }
            }
         })
        }
        //coupon end
    </script>

</body>

</html>
