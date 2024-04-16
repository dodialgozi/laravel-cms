<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="col-md-6 mb-3">
                    <x-form-select2 label="Jurnalis" name="{{ $input }}[user_id]" :url="url('user/select') . '?level=jurnalis'" key="id"
                        value="nama" :allowClear=true id="user_id" required>
                        @if (!empty(($user = $result ?? null)))
                            <option value="{{ $user->user_id }}">{{ $user->user_name }}</option>
                        @endif
                    </x-form-select2>
                </div>

                <div class="col-md-8 mb-3">
                    <x-form-select2 label="Kategori" name="{{ $input }}[categories][]" :url="url('post-kategori/select')"
                        key="id" value="nama" :allowClear=true id="categories" multiple required>
                        @if (!empty(($categories = $more['categories'] ?? null)))
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" selected>
                                    {{ $category->category->category_name }}</option>
                            @endforeach
                        @endif
                    </x-form-select2>
                </div>
            </div>
        </div>
    </div>
</div>
