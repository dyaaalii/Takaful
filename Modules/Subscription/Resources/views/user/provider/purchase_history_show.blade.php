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
                    <div class="wsus__profile_subdcription_overview">

                        <div class="wsus__profile_overview_table">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>{{__('user.Plan')}}</td>
                                            <td>{{ $history->plan_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Expiration')}}</td>
                                            <td>{{ $history->expiration }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{__('user.Expirated Date')}}</td>
                                            <td>{{ $history->expiration_date }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Remaining day')}}</td>
                                            <td>
                                                @if ($history->status == 'active')
                                                @if ($history->expiration_date == 'lifetime')
                                                    {{__('user.Lifetime')}}
                                                @else
                                                    @php
                                                        $date1 = new DateTime(date('Y-m-d'));
                                                        $date2 = new DateTime($history->expiration_date);
                                                        $interval = $date1->diff($date2);
                                                        $remaining = $interval->days;
                                                    @endphp

                                                    @if ($remaining > 0)
                                                        {{ $remaining }} {{__('user.Days')}}
                                                    @else
                                                        {{__('user.Expired')}}
                                                    @endif

                                                @endif
                                            @else
                                                {{__('user.Expired')}}
                                            @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Number of Product')}}</td>
                                            <td>{{$history->maximum_service}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Payment Method')}}</td>
                                            <td>{{$history->payment_method}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Transaction')}}</td>
                                            <td>{{$history->transaction}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{__('user.Payment')}}</td>
                                            <td>
                                                @if ($history->payment_status == 'success')
                                                    <strong>{{__('user.Success')}}</strong>
                                                @else
                                                    <strong>{{__('user.Pending')}}</strong>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{__('user.Status')}}</td>
                                            <td>
                                                @if ($history->status == 'active')
                                                    <strong>{{__('user.Active')}}</strong>
                                                @else
                                                    <strong>{{__('user.Expired')}}</strong>
                                                @endif
                                            </td>
                                        </tr>
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

