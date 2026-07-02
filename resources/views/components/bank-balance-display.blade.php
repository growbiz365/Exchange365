@props([
    'displayId' => 'bank_balance_display',
    'amountId' => 'bank_balance_amount',
])

<div id="{{ $displayId }}" class="mt-1 text-xs font-semibold text-gray-800 hidden">
    <span>Balance:</span>
    <span id="{{ $amountId }}" class="ml-1"></span>
</div>
