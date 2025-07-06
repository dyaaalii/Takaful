@extends($active_theme)

@section('title')
    <title>{{__('user.Paypal Gateway')}}</title>
    <meta name="description" content="{{__('user.Paypal Gateway')}}">
@endsection

@section('frontend-content')

    <!--=============================
        PROFILE PORTFOLIO START
    ==============================-->
    <section class="wsus__profile pt_130 xs_pt_100 pb_120 xs_pb_80">

        @include('user.inc.profile_header')

        <div class="wsus__subscription_area">
            <div class="row">
                @include('user.inc.sideber')
                <div class="col-xl-9 col-lg-8">

                </div>
            </div>
        </div>
        </div>
    </section>
    <!--=============================
        PROFILE PORTFOLIO END
    ==============================-->



@endsection

