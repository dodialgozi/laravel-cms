<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="col-md-6 mb-3">
                    <x-form-select2 label="User" name="{{ $input }}[user_id]" :url="url('user/select') . '?level=jurnalis'" key="id"
                        value="nama" :allowClear=true id="user_id" required>
                        @if (!empty(($user = $result ?? null)))
                            <option value="{{ $user->user_id }}">{{ $user->user_name }}</option>
                        @endif
                    </x-form-select2>
                </div>

                <div class="col-md-8 mb-3">
                    <x-form-select2 label="Instansi" name="{{ $input }}[instances][]" :url="url('instance/select')"
                        key="id" value="nama" :allowClear=true id="instances" multiple required>
                        @if (!empty(($instances = $more['instances'] ?? null)))
                            @foreach ($instances as $instance)
                                <option value="{{ $instance->instance_id }}" selected>
                                    {{ $instance->instance_name }}</option>
                            @endforeach
                        @endif
                    </x-form-select2>
                </div>
            </div>
        </div>
    </div>
</div>
