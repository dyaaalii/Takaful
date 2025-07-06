<style>
    .submenu {
        display: none;
        margin-left: 20px; /* Adjust the margin as needed */
    }

    .active_ul {
        display: block !important;
    }

    #payment_gateway_tab,
    #history_tab{
        cursor: pointer;
    }
</style>

<div class="col-xl-3 col-lg-4">
    <div class="wsus__subscription_sidebar">
        <ul>

            <li >
                <a href="{{  route('provider.view-all-order') }}" class="{{ Route::is('provider.view-all-order') || Route::is('provider.view-order-detils') ? 'active' : '' }}"><span><i
                            class="fas fa-shopping-basket"></i></span>
                    All Orders
                </a>
            </li>

            <li>
                <a id="history_tab" class="{{ Route::is('provider.purchase-history') || Route::is('provider.purchase-history-show') || Route::is('provider.pending-plan-payment') ? 'active' : '' }}"><span><i
                            class="fas fa-th-list"></i></span>
                    {{__('user.Subscription Plan')}}
                </a>
                <ul class="submenu history_list {{ Route::is('provider.purchase-history') || Route::is('provider.purchase-history-show') || Route::is('provider.pending-plan-payment') ? 'active_ul' : '' }}">
                    <li>
                        <a  class="{{  Route::is('provider.purchase-history') || Route::is('provider.purchase-history-show') ? 'active' : '' }}" href="{{route('provider.purchase-history')}}">{{__('user.Purchase History')}}</a></li>
                    <li>
                        <a  class="{{ Route::is('provider.pending-plan-payment') ? 'active' : '' }}" href="{{route('provider.pending-plan-payment')}}">{{__('user.Pending Payment')}}</a></li>
                </ul>
            </li>

            <li>
                <a id="payment_gateway_tab" class="{{ Route::is('provider.paypal-gateway') || Route::is('provider.stripe-gateway') || Route::is('provider.razorpay-gateway') || Route::is('provider.flutterwave-gateway') || Route::is('provider.paystack-gateway') || Route::is('provider.mollie-gateway') || Route::is('provider.instamojo-gateway') || Route::is('provider.bank-handcash-gateway') ? 'active' : '' }}">
                    <span><i class="fas fa-credit-card-front"></i></span>
                    {{__('user.Payment Gateway')}}
                </a>

                <ul class="submenu gateway_list {{ Route::is('provider.paypal-gateway') || Route::is('provider.stripe-gateway') || Route::is('provider.razorpay-gateway') || Route::is('provider.flutterwave-gateway') || Route::is('provider.paystack-gateway') || Route::is('provider.mollie-gateway') || Route::is('provider.instamojo-gateway') || Route::is('provider.bank-handcash-gateway') ? 'active_ul' : '' }}">
                <li ><a class="{{ Route::is('provider.paypal-gateway') ? 'active' : '' }}" href="{{route('provider.paypal-gateway')}}">{{__('user.Paypal Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.stripe-gateway') ? 'active' : '' }}" href="{{route('provider.stripe-gateway')}}">{{__('user.Stripe Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.razorpay-gateway') ? 'active' : '' }}" href="{{route('provider.razorpay-gateway')}}">{{__('user.Razorpay Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.flutterwave-gateway') ? 'active' : '' }}" href="{{route('provider.flutterwave-gateway')}}">{{__('user.Flutterwave Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.paystack-gateway') ? 'active' : '' }}" href="{{route('provider.paystack-gateway')}}">{{__('user.Paystack Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.mollie-gateway') ? 'active' : '' }}" href="{{route('provider.mollie-gateway')}}">{{__('user.Mollie Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.instamojo-gateway') ? 'active' : '' }}" href="{{route('provider.instamojo-gateway')}}">{{__('user.Instamojo Gateway')}}</a></li>

                <li><a class="{{ Route::is('provider.bank-handcash-gateway') ? 'active' : '' }}" href="{{route('provider.bank-handcash-gateway')}}">{{__('user.Bank & Handcash')}}</a></li>


                </ul>
            </li>

        </ul>
    </div>
</div>


@push('frontend_js')
<script>
    "use strict";
    $(document).ready(function(){

        $('#payment_gateway_tab').on('click',function(){

            $('.gateway_list').toggleClass('active_ul');
            $('.history_list').removeClass('active_ul');
        });

        $('#history_tab').on('click',function(){

            $('.history_list').toggleClass('active_ul');
            $('.gateway_list').removeClass('active_ul');
        });

    })
</script>

@endpush
