@props(['formId'])

<style>
    #{{ $formId }} input[type="text"],
    #{{ $formId }} input[type="number"],
    #{{ $formId }} input[type="date"],
    #{{ $formId }} textarea {
        font-weight: 600;
    }
    #{{ $formId }} .flatpickr-input,
    #{{ $formId }} input[id="date_added"],
    #{{ $formId }} input[id="sale_date"] {
        width: 100%;
        max-width: none;
        display: block;
        box-sizing: border-box;
        height: 32px;
        padding-top: 4px;
        padding-bottom: 4px;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
