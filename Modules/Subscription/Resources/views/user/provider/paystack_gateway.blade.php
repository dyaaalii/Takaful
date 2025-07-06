@extends($active_theme)

@section('title')
    <title>{{__('user.Paystack Gateway')}}</title>
    <meta name="description" content="{{__('user.Paystack Gateway')}}">
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
                    <div class="wsus__profile_subdcription_payment">
                        <h3>{{__('user.Paystack Gateway')}}</h3><br>
                        @if ($paystack)
                            <form action="{{ route('provider.store-paystack-gateway') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('user.Public Key')}}</label>
                                    <input type="text" name="public_key" class="form-control" value="{{ $paystack->public_key }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Secret Key')}}</label>
                                    <input type="text" name="secret_key" class="form-control" value="{{ $paystack->secret_key }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Status')}}</label>
                                    <select name="status" id="" class="select_js">
                                        <option {{ $paystack->status == 1 ? 'selected' : '' }} value="1">{{__('user.Active')}}</option>
                                        <option {{ $paystack->status == 0 ? 'selected' : '' }} value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <button class="common_btn" type="submit">{{__('user.Save')}}</button>

                            </form>
                        @else
                            <form action="{{ route('provider.store-paystack-gateway') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('user.Public Key')}}</label>
                                    <input type="text" name="public_key" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Secret Key')}}</label>
                                    <input type="text" name="secret_key" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Status')}}</label>
                                    <select name="status" id="" class="select_js">
                                        <option value="1">{{__('user.Active')}}</option>
                                        <option value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <button class="common_btn" type="submit">{{__('user.Save')}}</button>

                            </form>
                        @endif
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

