<textarea {{ $attributes->class(['form-control'])->merge(['rows' => 3]) }}>{{ exist($value, $slot) }}</textarea>