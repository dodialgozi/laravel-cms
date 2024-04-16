@extends('backend.layouts.panel')

@section('title', "{$title} Permission")

@section('panel_content')
<form id="formpermission" action="{{ url()->current() }}" method="POST">
    @csrf

    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>Permission Group</th>
                @foreach ($defaultPermission as $permName)
                <th>{{ $permName }}</th>
                @endforeach
                <th>Lainnya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($group as $value)
            <tr>
                <td>{{ $value->name }}</td>
                @foreach ($defaultPermission as $permKey => $permName)
                <td>
                    @php $perm = $permission[$value->id][$permKey] ?? null; @endphp
                    @if(!empty($perm))
                    <x-input-switch name="{{ $input }}[{{ $perm->id }}]" :checked="in_array($perm->id, $rolePermission)" :title=$permName :square=true />
                    @endif
                </td>
                @endforeach
                <td>
                    @php $perms = $permission[$value->id]['lainnya'] ?? []; @endphp
                    @foreach ($perms as $perm)
                    <div class="form-check form-check-primary check-size-md mb-2">
                        <input class="form-check-input" type="checkbox"
                            id="{{ $idCheck = randomGen2(20) }}" name="{{ $input }}[{{ $perm->id }}]" @if(in_array($perm->id, $rolePermission)) checked @endif>
                        <label class="form-check-label" for="{{ $idCheck }}">
                            <span @if(empty($perm->description)) class="fst-italic" @endif>
                                {{ $perm->description ?? $perm->name }}
                            </span>
                        </label>
                    </div>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Lainnya</td>
                <td colspan="6">
                    @php $perms = $permission['other']['lainnya'] ?? []; @endphp
                    @foreach ($perms as $perm)
                    <div class="form-check form-check-primary check-size-md mb-2">
                        <input class="form-check-input" type="checkbox"
                            id="{{ $idCheck = randomGen2(20) }}" name="{{ $input }}[{{ $perm->id }}]" @if(in_array($perm->id, $rolePermission)) checked @endif>
                        <label class="form-check-label" for="{{ $idCheck }}">
                            <span @if(empty($perm->description)) class="fst-italic" @endif>
                                {{ $perm->description ?? $perm->name }}
                            </span>
                        </label>
                    </div>
                    @endforeach
                </td>
            </tr>
        </tfoot>
    </table>

    <x-form-submit type="success" />
</form>
@endsection

@push('style')
<style>
.check-size-md {
    font-size: .9rem;
}
</style>
@endpush