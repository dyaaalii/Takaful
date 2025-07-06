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




            <div class="row pricing-two-bg ">

                <div class="col-xl-4 col-md-6">
                    <div class="wsus__single_pricing active">
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
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-md-6 ">
                    <div class="pricing-two-main">
                        @if ($stripe->status == 1)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#stripeModal">
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($stripe->image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if ($paypal->status == 1)
                            <a href="{{ route('provider.subscription.paypal-payment', $plan->id) }}" >
                                <div class="pricing-two-main-item">
                                    <div class="form-check">

                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($paypal->image) }}" alt="paypal">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif 

                        @if ($razorpay->status == 1)
                        <li class="nav-item">
                            <a  id="rajorpay">
                                <div class="pricing-two-main-item">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        <img src="{{ asset($razorpay->image) }}" alt="thumb">
                                    </label>
                                </div>
                            </a>
                            <form action="{{ route('provider.subscription.razorpay-payment', $plan->id) }}" class="d-none" method="POST" id="rajorpayForm">
                                @csrf
                                @php
                                    $plan_price = $plan->plan_price;
                                    $payableAmount = round($plan_price * $razorpay->currency->currency_rate,2);
                                @endphp
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                    data-key="{{ $razorpay->key }}"
                                    data-currency="{{ $razorpay->currency->currency_code }}"
                                    data-amount= "{{ $payableAmount * 100 }}"
                                    data-buttontext="{{__('user.Pay')}} {{ $payableAmount }} {{ $razorpay->currency->currency_code }}"
                                    data-name="{{ $razorpay->name }}"
                                    data-description="{{ $razorpay->description }}"
                                    data-image="{{ asset($razorpay->image) }}"
                                    data-prefill.name=""
                                    data-prefill.email=""
                                    data-theme.color="{{ $razorpay->color }}">
                                </script>
                            </form>
                        </li>
                        @endif

                        @if ($flutterwave->status == 1)
                            <a onclick="paywithFlutterwave()" >
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($flutterwave->logo) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if ($paystack->paystack_status == 1)
                            <a onclick="payWithPaystack()" >
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($paystack->paystack_image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if ($mollie->mollie_status == 1)
                            <a href="{{ route('provider.subscription.mollie-payment', $plan->id) }}">
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($mollie->mollie_image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if ($instamojo->status == 1)
                            <a href="{{ route('provider.subscription.instamojo-payment', $plan->id) }}">
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($instamojo->image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif

                        @if ($bank_payment->status == 1)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#bankPaymentModal">
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($bank_payment->image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif
                         @if ($sslcommerzPayment->status == 1)
                            <a href="{{ route('provider.subscription.sslecommerce-payment', $plan->id) }}" >
                                <div class="pricing-two-main-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="hidden" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            <img src="{{ asset($sslcommerzPayment->image) }}" alt="thumb">
                                        </label>
                                    </div>
                                </div>
                            </a>
                        @endif 
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Stripe Modal -->
    <div class="modal fade" id="stripeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('user.Pay via Stripe')}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $stripe = App\Models\StripePayment::first();
                @endphp
            <form  role="form" action="{{ route('provider.subscription.stripe-payment', $plan->id) }}" method="post"
            class="require-validation"
            data-cc-on-file="false"
            data-stripe-publishable-key="{{ $stripe->stripe_key }}"
            id="payment-form">
            @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__comment_single_input">
                            <fieldset>
                                <legend>{{__('user.Card Number')}}*</legend>
                                <input type="text" name="card_number" class="card-number" autocomplete="off">
                            </fieldset>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="wsus__comment_single_input">
                            <fieldset>
                                <legend>{{__('user.Month')}}*</legend>
                                <input type="text" size="2" name="month" class="card-expiry-month"  autocomplete="off">
                            </fieldset>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="wsus__comment_single_input">
                            <fieldset>
                                <legend>{{__('user.Year')}}*</legend>
                                <input type="text" size="4" name="year" class="card-expiry-year" autocomplete="off">
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="wsus__comment_single_input">
                            <fieldset>
                                <legend>{{__('user.CVC')}}*</legend>
                                <input type="text" size="4" name="cvc"  class="card-cvc" autocomplete="off">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12 error d-none'>
                        <div class='alert-danger alert'>{{__('user.Please correct the errors and try again')}}.</div>
                    </div>
                </div>

            <div class="modal-footer">
                <button class="common_btn btn-block" type="submit">{{__('user.Payment')}}</button>
            </div>
            </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Stripe Modal -->


    <!-- Modal -->
    <div class="modal fade" id="bankPaymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('user.Bank Payment')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        {!! nl2br($bank_payment->account_info) !!}
                    </div>
                    <form class="wsus__contact_form wsus__comment_input_area" action="{{ route('provider.subscription.bank-payment', $plan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="wsus__comment_single_input">
                                    <fieldset>
                                        <legend>{{__('user.Transaction')}}*</legend>
                                        <textarea name="transaction" required></textarea>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button class="common_btn btn-block" type="submit">{{__('user.Submit')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!--=============================
        PRICING END
    ==============================-->

    @php
        $plan_price = $plan->plan_price;

        // start paystack
        $public_key = $paystack->paystack_public_key;
        $currency = $paystack->paystack_currency_code;
        $currency = strtoupper($currency);

        $ngn_amount = $plan_price * $paystack->paystack_currency_rate;
        $ngn_amount = $ngn_amount * 100;
        $ngn_amount = round($ngn_amount);
        // end paystack

        // start fluttewave
        $payable_amount = $plan_price * $flutterwave->currency_rate;
        $payable_amount = round($payable_amount, 2);
    @endphp





@endsection



{{-- stripe payment --}}
@push('frontend_js')

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {
            "use strict";

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
                $errorMessage.addClass('d-none');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault();
                }
                });

                if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>

@endpush
{{-- stripe payment --}}

{{-- rajorpay payment --}}
@push('frontend_js')
    <script>
        "use strict";
        $(document).ready(function(){
            $('#rajorpay').on('click',function(){
                $('#rajorpayForm').submit();
            });
        })
    </script>
@endpush
{{-- rajorpay payment --}}

{{-- rajorpay payment --}}
@push('frontend_js')
<script src="https://checkout.flutterwave.com/v3.js"></script>
@php
    $payable_amount = $plan_price * $flutterwave->currency->currency_rate;
    $payable_amount = round($payable_amount, 2);
@endphp
<script>
    "use strict";
    function paywithFlutterwave() {

        var isDemo = "{{ env('APP_MODE') }}"
        if(isDemo == 'DEMO'){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }


        FlutterwaveCheckout({
            public_key: "{{ $flutterwave->public_key }}",
            tx_ref: "RX1",
            amount: {{ $payable_amount }},
            currency: "{{ $flutterwave->currency->currency_code }}",
            country: "{{ $flutterwave->currency->country_code }}",
            payment_options: " ",
            customer: {
            email: "{{ $user->email }}",
            phone_number: "{{ $user->phone }}",
            name: "{{ $user->name }}",
            },
            callback: function (data) {

                var tnx_id = data.transaction_id;
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    type: 'post',
                    data : {tnx_id,_token},
                    url: "{{ url('provider.subscription.flutterwave-payment') }}",
                    success: function (response) {
                        if(response.status == 'success'){
                            toastr.success(response.message);
                            window.location.href = "{{ route('provider.purchase-history') }}";
                        }else{
                            toastr.error(response.message);
                            window.location.reload();
                        }
                    },
                    error: function(err) {

                    }
                });
            },
            customizations: {
            title: "{{ $flutterwave->title }}",
            logo: "{{ asset($flutterwave->logo) }}",
            },
        });
    }
</script>
@endpush
{{-- rajorpay payment --}}
{{-- Playstack payment --}}
@push('frontend_js')
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack(){
        var plan_id = '{{ $plan->id }}';

        var handler = PaystackPop.setup({
            key: '{{ $public_key }}',
            email: '{{ $user->email }}',
            amount: '{{ $ngn_amount }}',
            currency: "{{ $currency }}",
            callback: function(response){
            let reference = response.reference;
            let tnx_id = response.transaction;
            let _token = "{{ csrf_token() }}";
            $.ajax({
                type: "post",
                data: {reference, tnx_id, _token, plan_id},
                url: "{{ route('provider.subscription.paystack-payment') }}",
                success: function(response) {
                    if(response.status == 'success'){
                        window.location.href = "{{ route('provider.purchase-history') }}";
                    }else{
                        window.location.reload();
                    }
                }
            });
            },
            onClose: function(){
                alert('window closed');
            }
        });
        handler.openIframe();
    }
</script>
    
@endpush
{{-- Playstack payment --}}


