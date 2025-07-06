@extends($active_theme)

@section('title')
    <title>{{__('user.Order Detils')}}</title>
    <meta name="description" content="{{__('user.Order Detils')}}">
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
                    <div class="wsus__profile_subdcription_invoice">

                        <div class="profile_invoice_header">
                            <div class="logo">
                                <img src="{{ asset($setting->logo) }}" alt="Alasmart" class="img-fluid w-100">
                            </div>

                        </div>
                        @php
                            $orderAddress = $order->user;
                        @endphp

                        <div class="profile_invoice_billing_info">
                            <div class="info_text">
                                <h3>{{__('user.User Information')}}</h3>
                                <p>{{ $orderAddress->name }}</p>
                                @if ($orderAddress->phone)
                                    <p>{{ $orderAddress->phone }}y</p>
                                @endif
                                @if ($orderAddress->email)
                                    <p>{{ $orderAddress->email }}</p>
                                @endif
                                <p>{{ $orderAddress->address }}</p>
                            </div>
                            <div class="info_text">
                                <div class="invoice_date pt-5" >
                                    <p></p>
                                    <p><span>Invoice No:</span>  <b>#{{ $order->order_id }}</b></p>
                                    <p><span>Date:</span><strong>{{ Carbon\Carbon::parse($order->created_at)->format('d F, Y') }}</strong>  </p>
                                </div>
                            </div>

                        </div>

                        <div class="profile_invoice_summery">
                            <h3>{{__('user.Item Summery')}}</h3>
                            <div class="profile_invoice_table">
                                <div class="table-responsive">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th class="no">no</th>
                                                <th class="items">items</th>
                                                <th class="type">Price Type</th>
                                                <th class="author">Author</th>
                                                <th class="price">Unit Price</th>
                                                <th class="total">Total</th>
                                            </tr>
                                            @foreach ($order->orderItems as $index => $item)
                                                <tr>
                                                    <td class="no">{{ ++$index }}</td>
                                                    <td class="items">{{ html_decode($item->product->productlangfrontend->name) }}</td>
                                                    <td class="type">{{ ucfirst($item->product->product_type) }}</td>
                                                    <td class="author">{{  $item->author->name }}</td>
                                                    <td class="price">
                                                        {{ $setting->currency_icon }}{{ $item->price }}
                                                    </td>
                                                    @php
                                                        $total = ($item->price * $item->qty)
                                                    @endphp
                                                    <td class="total">{{ $setting->currency_icon }}{{ $total }}</td>
                                                </tr>
                                           @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="profile_invoice_footer">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-5 col-md-6">
                                            <div class="profile_invoice_footer_left">
                                                <h4>{{__('user.Payment info') }}:</h4>
                                                <p>{{ $order->payment_method }} - {{ $order->transection_id }}</p>
                                                <p>{{__('user.Amount') }}: {{ $setting->currency_icon }}{{ $total }}</p>
                                                <p>{{__('user.Status') }}: {{$order->payment_status}}</p>
                                            </div>
                                        </div>
                                        @php
                                            $sub_total = $order->total_amount;
                                            $sub_total = $sub_total - $order->shipping_cost;
                                            $sub_total = $sub_total + $order->coupon_coast;
                                        @endphp
                                        <div class="col-xl-4 col-md-5">
                                            <div class="profile_invoice_footer_right">
                                                <h4><span>Subtotal:</span> {{ $setting->currency_icon }}{{ round($order->total_amount, 2) }}</h4>
                                                <h4><span>total:</span> {{ $setting->currency_icon }}{{ round($order->total_amount, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!--=============================
        PROFILE PORTFOLIO END
    ==============================-->



@endsection

