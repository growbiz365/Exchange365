@props(['for'])

<p
    id="{{ $for }}_words"
    {{ $attributes->merge(['class' => 'amount-in-words mt-1 text-xs text-gray-600 italic leading-snug hidden']) }}
    aria-live="polite"
></p>
