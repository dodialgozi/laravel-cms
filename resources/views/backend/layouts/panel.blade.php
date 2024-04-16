@extends('backend.layouts.app')

@section('content')
    <div class="card">
        @if (view()->hasSection('panel_right') || view()->hasSection('show_title'))
            <div class="card-header align-items-center d-flex justify-content-end">
                @if (view()->hasSection('show_title'))
                    <h4 class="card-title mb-0 flex-grow-1"> @yield('title')</h4>
                @endif
                <div class="flex-shrink-0">
                    @yield('panel_right')
                </div>
            </div>
        @endif

        <div class="card-body">
            @yield('panel_content')
        </div>
    </div>
@endsection
