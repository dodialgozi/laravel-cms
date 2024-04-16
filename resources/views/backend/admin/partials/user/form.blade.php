@php
    $levels = $more['levels'] ?? [];
@endphp
<div class="row">
    <div class="col-md-6 mb-3">
        <x-form-select2 label="Role" name="role" :url="url('role/select')" key="id" value="nama" :allowClear=true id="role">
        @if(!empty($role = $more['role'] ?? null))
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endif
        </x-form-select2>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-select2-option label="Level" name="{{ $input }}[user_level]" :options=$levels value="{{ $result->user_level ?? 'jurnalis' }}" :disableSearch=true :allowClear=true/>
    </div>

    <div class="col-md-12"></div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Email" name="{{ $input }}[user_email]" value="{{ $result->user_email ?? '' }}" id="email" required/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Password" name="{{ $input }}[user_password]" value="{{ $formType == 'create' ? '123' : decode($result->user_password ?? null) }}" type="password" required/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Nama" name="{{ $input }}[user_name]" value="{{ $result->user_name ?? '' }}" required/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-input label="Panggilan" name="{{ $input }}[user_nick]" value="{{ $result->user_nick ?? '' }}"/>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-textarea label="Bio" name="{{ $input }}[user_bio]">{!! $result->user_bio ?? '' !!}</x-form-textarea>
    </div>
    <div class="col-md-6 mb-3">
        <x-form-file label="Foto" name="{{ $inputFile }}[user_photo]" :value="$result->user_photo ?? null" :image=true :download=false />
    </div>
    <div class="col-md-6 mb-3">
        <x-form-switch label="Status Aktif" name="{{ $input }}[user_active]" :checked="!empty($result->user_active ?? 1)" labelOn="Ya" labelOff="Tdk" />
    </div>
    
</div>

@push('script')
<script>
var cabang = '';
$(function() {
    @if($formType == 'create')
    let changeEmail = true;

    $('#email').keyup(function() {
        if(changeEmail) changeEmail = false;
    });
    @endif
});
</script>
@endpush