@extends($active_theme)

@section('title')
    <title>{{__('user.All Order')}}</title>
    <meta name="description" content="{{__('user..All Order')}}">
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
                    <div class="wsus__profile_order">


                        <div class="wsus__profile_order_table">
                            <div class="table-responsive">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th class="sn">{{__('user.SN')}}</th>
                                            <th class="date">{{__('user.Date')}}</th>
                                            <th class="customer">{{__('user.Customer')}}</th>
                                            <th class="order">{{__('user.Order Id')}}</th>
                                            <th class="amount">{{__('user.Amount')}}</th>
                                            <th class="status">{{__('user.Status')}}</th>
                                            <th class="payment">{{__('user.Payment')}}</th>
                                            <th class="action">{{__('user.Action')}}</th>
                                        </tr>

                                        @foreach ($orders as $index => $order)
                                            <tr>
                                                <td class="sn">{{ ++$index }}</td>
                                                <td class="date">{{ Carbon\Carbon::parse($order->created_at)->format('d F, Y') }}</td>
                                                <td class="customer">{{ $order->user->name}}</td>
                                                <td class="order">{{ $order->order_id }}</td>
                                                <td class="amount">{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                                @if ($order->order_status == 1)
                                                    <td class="status"><span class="complete">{{__('user.Complete')}}</span></td>
                                                @elseif ($order->order_status == 0)
                                                    <td class="status"><span class="cancel">{{__('user.Pending')}}</span></td>
                                                @endif

                                                @if($order->payment_status == 'success')
                                                    <td class="payment"><span class="success">{{__('user.Success')}} </span></td>
                                                @else
                                                    <td class="payment"><span class="delete">{{__('user.Pending')}}</span></td>
                                                @endif

                                                <td class="action">
                                                    <a class="view" href="{{route('provider.view-order-detils',$order->id)}}"><i class="fal fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

