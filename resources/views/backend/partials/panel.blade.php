@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">@yield('title')</h4>
                <p class="card-title-desc">@yield('description')</p>

                @yield('panel_body')
            </div>
        </div>
    </div>
</div>
@endsection
