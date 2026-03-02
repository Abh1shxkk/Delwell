@props(['src' => null, 'alt' => 'Profile', 'size' => 'h-64'])

@if($src)
    <img {{ $attributes->merge(['class' => "w-full {$size} object-cover"]) }} src="{{ $src }}" alt="{{ $alt }}" />
@else
    <div {{ $attributes->merge(['class' => "w-full {$size} bg-gray-100 flex items-center justify-center"]) }}>
        <x-profile-placeholder class="w-24 h-24 text-gray-400" />
    </div>
@endif