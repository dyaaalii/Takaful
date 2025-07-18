<div class="wsus__profile_header" style="background: url({{ custom_asset('frontend/images/profile_header_bg.jpg') }});">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__profile_header_text">
                    <div class="img wow fadeInLeft" data-wow-duration="1s">
                        @if ($user->image!=null)
                        <img src="{{ custom_asset($user->image) }}" alt="profile" class="img-fluid w-100">
                        @elseif($user->provider=='google')
                        <img src="{{ custom_asset($user->provider_avatar) }}" alt="profile" class="img-fluid w-100">
                        @else
                        <img src="{{ custom_asset($setting->default_avatar) }}" alt="profile" class="img-fluid w-100">
                        @endif
                    </div>
                    <div class="text wow fadeInRight" data-wow-duration="1s">
                        <h2>{{ html_decode($user->name) }}</h2>
                        <p class="join"><span>{{__('user.Joined')}}:</span> {{ Carbon\Carbon::parse($user->created_at)->format('F Y') }}</p>
                        <p class="skills">{{ html_decode($user->designation) }}</p>
                        @php
                            $review=App\Models\Review::where(['author_id' => $user->id, 'status' => 1])->get()->average('rating');
                            $votes=App\Models\Review::where(['author_id' => $user->id, 'status' => 1])->get()->count();
                            $total_product=App\Models\Product::with('category','author')->where(['author_id' => $user->id, 'status' => 1])->get()->count();
                            $total_sold=App\Models\OrderItem::with('product', 'variant', 'order')->where('author_id', $user->id)->get()->count();
                        @endphp
                        <div class="rating">
                            <span>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review)
                                    <i class="fas fa-star"></i>
                                    @else
                                    <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </span>
                            <p>{{__('user.Average rating of')}} {{ $review==0?0:round($review, 1) }} {{__('user.based on')}} {{ $votes }} {{__('user.votes')}}</p>
                        </div>
                    </div>
                    <ul class="header_button d-flex flex-wrap">
                        <li>
                            <h4><i class="fas fa-box-full"></i> {{ $total_product }}</h4>
                            <p>{{__('user.Products')}}</p>
                        </li>
                        <li>
                            <h4><i class="fas fa-cloud-download-alt"></i> {{ $total_sold }}</h4>
                            <p>{{__('user.Total sale')}}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <ul class="header_menu d-flex flex-wrap">
        <li><a class="{{ Route::is('author-profile') ? 'active' : '' }}" href="{{ route('author-profile', $user->user_name) }}"><i class="fal fa-layer-group"></i> {{__('user.Overview')}}</a>
        </li>
        <li><a class="{{ Route::is('author-portfolio') ? 'active' : '' }}" href="{{ route('author-portfolio', $user->user_name) }}"><i class="far fa-box"></i> {{__('user.Portfolio')}}</a></li>
    </ul>
