@extends('backend.layouts.app')

@section('title', 'Dashboard')

@section('hide_breadcrumb', true)

@section('content')
    @if (getLevel() != 'administrator' && empty(getInstanceId()))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Selamat Datang</h4>
                        <p class="card-text">Silahkan pilih instance yang akan dikelola.</p>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            @foreach (auth()->user()->instances as $item)
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">{{ $item->instance_name }}</h5>
                                            <p class="card-text">{{ $item->instance_domain }}</p>
                                            <a href="{{ url('instance/set/' . encodeId($item->instance_id)) }}"
                                                class="btn btn-primary">Pilih</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12 order-1 order-md-0">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Published Post</p>
                                        <h4 class="mb-0">{{ number_format($publishedPost) }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                            <span class="avatar-title">
                                                <i class="bx bx-file font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <!-- full width button -->
                                <a href="{{ route('post.create') }}" class="w-100 btn btn-block btn-success">Create Post</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Draft Post</p>
                                        <h4 class="mb-0">{{ number_format($draftPost) }}</h4>
                                    </div>
                                    <div class="flex-shrink-0 align-self-center ">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-file-blank font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <!-- full width button -->
                                <a href="{{ route('post.create') }}" class="w-100 btn btn-block btn-primary">Create Post</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Published Page</p>
                                        <h4 class="mb-0">{{ number_format($publishedPage) }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-user-circle font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <!-- full width button -->
                                <a href="{{ route('page.create') }}" class="w-100 btn btn-block btn-success">Create Page</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Draft Page</p>
                                        <h4 class="mb-0">{{ number_format($draftPage) }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                <i class="bx bx-user font-size-24"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <!-- full width button -->
                                <a href="{{ route('page.create') }}" class="w-100 btn btn-block btn-primary">Create
                                    Page</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 order-0 order-md-1">
                <h1 class="card-title mb-4">Pengunjung</h1>
            </div>

            <div class="col-md-3 order-0 order-md-1">
                <div class="card">
                    <div class="card-body">
                        <!-- center -->
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                    <i class="bx bx-user font-size-36"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">Total Pengunjung</h5>
                            <h4>{{ number_format($visitorToday) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 order-0 order-md-1">
                <div class="card">
                    <div class="card-body">
                        <!-- center -->
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                    <i class="bx bx-user font-size-36"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">Total Pengunjung Bulan Ini</h5>
                            <h4>{{ number_format($visitorThisMonth) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 order-0 order-md-1">
                <div class="card">
                    <div class="card-body">
                        <!-- center -->
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                    <i class="bx bx-user font-size-36"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">Total Tahun Ini</h5>
                            <h4>{{ number_format($visitorThisYear) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 order-0 order-md-1">
                <div class="card">
                    <div class="card-body">
                        <!-- center -->
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-4">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-24">
                                    <i class="bx bx-user font-size-36"></i>
                                </span>
                            </div>
                            <h5 class="font-size-15">Total Pengunjung</h5>
                            <h4>{{ number_format($visitorTotal) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body mb-0">
                        <h4 class="card-title">Recent Post</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($recent_post as $item)
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <span class="card-text h5">{{ $item->post_excerpt_id }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span
                                                    class="card-text h5 text-white badge bg-info">{{ $item->post_status }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6 order-1 order-md-0">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Popular Post</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($popular_post as $item)
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <span class="card-text h5">{{ $item->post->post_excerpt_id }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <span
                                                    class="card-text h5 text-white badge bg-info">{{ $item->post->post_status }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
