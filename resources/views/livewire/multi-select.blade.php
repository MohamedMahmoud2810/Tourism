<div>
    <select wire:model="grades" multiple>
        @foreach($grades as $grade)
            <option value="{{ $grade }}">{{ $grade }}</option>
        @endforeach
    </select>
</div>
