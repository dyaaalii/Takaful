@extends($active_theme)

@section('title')
    <title>{{__('user.Instamojo Gateway')}}</title>
    <meta name="description" content="{{__('user.Instamojo Gateway')}}">
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
                        <h3>{{__('user.Instamojo Gateway')}}</h3><br>
                        @if ($instamojo)
                            <form action="{{ route('provider.store-instamojo-gateway') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="">{{__('user.Account Mode')}}</label>
                                    <select name="account_mode" id="" class="select_js">
                                        <option {{ $instamojo->account_mode == 'Sandbox' ? 'selected' : '' }} value="Sandbox">{{__('user.Sandbox')}}</option>
                                        <option {{ $instamojo->account_mode == 'Live' ? 'selected' : '' }} value="Live">{{__('user.Live')}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.API Key')}}</label>
                                    <input type="text" name="api_key" class="form-control" value="{{ $instamojo->api_key }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Auth Token')}}</label>
                                    <input type="text" name="auth_token" class="form-control" value="{{ $instamojo->auth_token }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Status')}}</label>
                                    <select name="status" id="" class="select_js">
                                        <option {{ $instamojo->status == 1 ? 'selected' : '' }} value="1">{{__('user.Active')}}</option>
                                        <option {{ $instamojo->status == 0 ? 'selected' : '' }} value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <button class="common_btn" type="submit">{{__('user.Save')}}</button>

                            </form>
                        @else
                            <form action="{{ route('provider.store-instamojo-gateway') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="">{{__('user.Account Mode')}}</label>
                                    <select name="account_mode" id="" class="select_js">
                                        <option value="Sandbox">{{__('user.Sandbox')}}</option>
                                        <option value="Live">{{__('user.Live')}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.API Key')}}</label>
                                    <input type="text" name="api_key" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Auth Token')}}</label>
                                    <input type="text" name="auth_token" class="form-control">
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

