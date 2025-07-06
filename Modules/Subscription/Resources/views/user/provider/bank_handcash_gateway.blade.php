@extends($active_theme)

@section('title')
    <title>{{__('user.Bank & Handcash')}}</title>
    <meta name="description" content="{{__('user.Bank & Handcash')}}">
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
                        <h3>{{__('user.Bank & Handcash')}}</h3><br>
                        @if ($bank_handcash)
                            <form action="{{ route('provider.store-bank-handcash-gateway') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="">{{__('user.Bank Instruction')}}</label>
                                    <textarea name="bank_instruction" class="form-control text-area-5" id="" cols="30" rows="3">{{ $bank_handcash->bank_instruction }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Bank Status')}}</label>
                                    <select name="bank_status" id="" class="select_js">
                                        <option {{ $bank_handcash->bank_status == 1 ? 'selected' : '' }} value="1">{{__('user.Active')}}</option>
                                        <option {{ $bank_handcash->bank_status == 0 ? 'selected' : '' }} value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Handcash Status')}}</label>
                                    <select name="handcash_status" id="" class="select_js">
                                        <option {{ $bank_handcash->handcash_status == 1 ? 'selected' : '' }} value="1">{{__('user.Active')}}</option>
                                        <option {{ $bank_handcash->handcash_status == 0 ? 'selected' : '' }} value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <button class="common_btn" type="submit">{{__('user.Save')}}</button>

                            </form>
                        @else
                            <form action="{{ route('provider.store-bank-handcash-gateway') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('user.Bank Instruction')}}</label>
                                    <textarea name="bank_instruction" class="form-control text-area-5" id="" cols="30" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('user.Bank Status')}}</label>
                                    <select name="bank_status" id="" class="select_js">
                                        <option value="1">{{__('user.Active')}}</option>
                                        <option value="0">{{__('user.Inactive')}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('user.Handcash Status')}}</label>
                                    <select name="handcash_status" id="" class="select_js">
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

