@extends($active_theme)
@section('title')
    <title>{{__('user.Pricing')}}</title>
@endsection

@section('frontend-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset('frontend/images/breadcrumb_bg.jpg') }});">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wsus__breadcrumb_text">
                        <h1>{{__('user.Pricing')}}</h1>
                        <ul class="d-flex flex-wrap">
                            <li><a href="{{ route('home') }}">{{__('user.home')}}</a></li>
                            <li><a href="javascript:;">{{__('user.Pricing')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

    <!--=============================
        PRICING START
    ==============================-->
    <section class="wsus__pricing mt_115 xs_mt_75 pb_120 xs_pb_80">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 m-auto">
                    <div class="wsus__section_heading mb_20">
                        <h5>{{__('user.Our Pricing Plan')}}</h5>
                        <h2>{{__('user.Pricing For Your Company')}}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($plans as $index => $plan )
                    <div class="col-xl-4 col-md-6">
                        <div class="wsus__single_pricing">
                            <h2>
                                @if ($setting->currency_position == 'before_price')
                                {{ sprintf('%0.2f', $plan->plan_price) }}<sup>{{ $setting->currency_icon }}</sup>
                                <sub>/
                                    @if ($plan->expiration_date == 'monthly')
                                    {{__('user.Monthly')}}

                                    @elseif ($plan->expiration_date == 'yearly')
                                        {{__('user.Yearly')}}

                                    @elseif ($plan->expiration_date == 'lifetime')

                                    {{__('user.Lifetime')}}
                                    @endif
                                </sub>
                                @else
                                <sup>{{ $setting->currency_icon }}</sup>{{ sprintf('%0.2f', $plan->plan_price) }}
                                <sub>/
                                    @if ($plan->expiration_date == 'monthly')
                                    {{__('user.Monthly')}}

                                    @elseif ($plan->expiration_date == 'yearly')
                                        {{__('user.Yearly')}}

                                    @elseif ($plan->expiration_date == 'lifetime')

                                    {{__('user.Lifetime')}}
                                    @endif
                                </sub>
                                @endif
                            </h2>
                            <div class="icon">
                                <img src="{{ asset($plan->icon) }}" alt="pricing" class="img-fluid w-100">
                            </div>
                            <div class="wsus__single_pricing_list">
                                <h3>{{ $plan->plan_name }}</h3>
                                <ul>
                                    <li>{{ $plan->maximum_service }} {{__('user.Product Upload')}} </li>
                                    <li>{{__('user.Exclusive Landing Page')}}</li>
                                    <li> {{__('user.Modules')}}</li>
                                    <li>{{__('user.Different Layouts')}}</li>
                                    <li>{{__('user.Support Ticket')}}</li>
                                    <li>{{__('user.Button Style')}}</li>
                                </ul>
                                @if ($plan->plan_price == 0)
                                    <a class="common_btn" href="{{ route('provider.subscription.free-enroll', $plan->id) }}">{{__('user.Trail Now')}}</a>
                                @else
                                    <a class="common_btn" href="{{ route('provider.subscription-payment', $plan->id) }}">{{__('user.Purchase Now')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--=============================
        PRICING END
    ==============================-->

@endsection
