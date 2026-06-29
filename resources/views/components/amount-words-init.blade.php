@props(['ids' => []])

<script src="{{ asset('js/amount-in-words.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.AmountInWords) {
        AmountInWords.bindAll(@json($ids));
    }
});
</script>
