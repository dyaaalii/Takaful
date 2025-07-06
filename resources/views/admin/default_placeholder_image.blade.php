@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Default Placeholder')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Default Placeholder')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Default Placeholder')}}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.update-default-placeholder') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">{{__('admin.Existing placeholder')}}</label>
                                <div>
                                    <img class="w_120" src="{{ custom_asset($default_placeholder) }}" alt="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">{{__('admin.New placeholder')}}</label>
                                <input type="file" name="placeholder" class="form-control-file" required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

@endsection
