@extends($active_theme)

@section('title')
    <title>{{__('user.Purchase History')}}</title>
    <meta name="description" content="{{__('user.Purchase History')}}">
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
                                            <th class="sn">SN</th>
                                            <th class="date">Plan</th>
                                            <th class="customer">Expiration</th>
                                            <th class="order">Expired Date</th>
                                            <th class="status">Status</th>
                                            <th class="payment">Payment</th>
                                            <th class="action">Action</th>
                                        </tr>

                                        @foreach ($histories as $index => $history)
                                            <tr>
                                                <td class="sn">{{ ++$index }}</td>

                                                <td class="date">{{ $history->plan_name }}</td>

                                                <td class="customer">{{ $history->expiration }}</td>
                                                <td class="order">{{ $history->expiration_date }}</td>

                                                <td class="status">
                                                    @if ($history->status == 'active')
                                                        <span class="complete">{{__('user.Active')}}</span>
                                                    @elseif ($history->status == 'pending')
                                                        <span class="cancel">{{__('user.Pending')}}</span>
                                                    @elseif ($history->status == 'expired')
                                                        <span class="cancel">{{__('user.Expired')}}</span>
                                                    @endif
                                                </td>

                                                <td class="payment">
                                                    @if ($history->payment_status == 'success')
                                                        <span class="success">{{__('user.Success')}}</span>
                                                    @else
                                                        <span class="cancel">{{__('user.Pending')}}</span>
                                                    @endif
                                                </td>
                                                <td class="action">
                                                    <a class="view" href="{{ route('provider.purchase-history-show', $history->id) }}"><i
                                                        class="fal fa-eye"></i></a>
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

