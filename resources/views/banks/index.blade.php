<x-app-layout>
    @section('title', 'Banks - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Banks Dashboard'],
        ['url' => route('banks.index'), 'label' => 'Banks']
    ]" />

    <x-dynamic-heading title="All Banks" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mt-4 mb-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <x-search-form
                    action="{{ route('banks.index') }}"
                    placeholder="Search by bank name or account..."
                    name="bank_name"
                    value="{{ request('bank_name') }}"
                />
                <select name="status"
                    class="rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    onchange="window.location.href='{{ route('banks.index') }}?status='+this.value+'&bank_name={{ request('bank_name') }}'">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <x-button href="{{ route('banks.create') }}">Add Bank</x-button>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif

    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    <x-table-wrapper>
        <thead class="bg-gray-50">
            <tr>
                <x-table-header>#</x-table-header>
                <x-table-header>Bank Name</x-table-header>
                <x-table-header>Currency</x-table-header>
                <x-table-header>Account #</x-table-header>
                <x-table-header>Type</x-table-header>
                <x-table-header>Opening Balance</x-table-header>
                <x-table-header>Status</x-table-header>
            </tr>
        </thead>
        <tbody>
            @forelse($banks as $bank)
                <tr
                    onclick="window.location.href='{{ route('banks.edit', $bank) }}'"
                    class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                    title="Click to edit bank"
                >
                    <x-table-cell>{{ $bank->bank_id }}</x-table-cell>
                    <x-table-cell>
                        <div>
                            <div class="font-medium">{{ $bank->bank_name }}</div>
                            @if($bank->created_at)
                                <div class="text-sm text-gray-500">Since: {{ $bank->created_at->format('d M Y') }}</div>
                            @endif
                        </div>
                    </x-table-cell>
                    <x-table-cell>{{ $bank->currency?->currency ?? '—' }} ({{ $bank->currency?->currency_symbol ?? '—' }})</x-table-cell>
                    <x-table-cell>{{ $bank->account_number ?? 'N/A' }}</x-table-cell>
                    <x-table-cell>{{ $bank->bankType?->bank_type ?? '—' }}</x-table-cell>
                    <x-table-cell>{{ number_format($bank->opening_balance, 2) }}</x-table-cell>
                    <x-table-cell>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full border {{ $bank->status == 1 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                            {{ $bank->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </x-table-cell>
                </tr>
            @empty
                <tr>
                    <x-table-cell colspan="7" class="text-center text-gray-500">No banks found. <a href="{{ route('banks.create') }}" class="text-indigo-600 hover:underline">Add your first bank</a>.</x-table-cell>
                </tr>
            @endforelse
        </tbody>
    </x-table-wrapper>
</x-app-layout>
