<x-app-layout>
    @section('title', 'Parties List - Party Management - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard'],
        ['url' => route('parties.index'), 'label' => 'All Parties']
    ]" />

    <x-dynamic-heading title="All Parties" />
    

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mt-4 mb-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                <x-search-form 
                    action="{{ route('parties.index') }}" 
                    placeholder="Search by name or contact..." 
                    name="party_name"
                    value="{{ request('party_name') }}"
                />
                <select name="status" 
                    class="rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                    onchange="window.location.href='{{ route('parties.index') }}?status='+this.value+'&party_name={{ request('party_name') }}'">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="w-full sm:w-auto">
                    <x-button href="{{ route('parties.create') }}">Add Party</x-button>
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
                <x-table-header>Party Name</x-table-header>
                <x-table-header>Contact</x-table-header>
                <x-table-header>Type</x-table-header>
                <x-table-header>Opening Balances</x-table-header>
                <x-table-header>Status</x-table-header>
            </tr>
        </thead>
        <tbody>
            @forelse($parties as $party)
                <tr
                    onclick="window.location.href='{{ route('parties.edit', $party) }}'"
                    class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                    title="Click to edit party"
                >
                    <x-table-cell>{{ $party->party_id }}</x-table-cell>
                    <x-table-cell>
                        <div>
                            <div class="font-medium">{{ $party->party_name }}</div>
                            <div class="text-sm text-gray-500">Since: {{ $party->opening_date->format('d M Y') }}</div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>{{ $party->contact_no ?? 'N/A' }}</x-table-cell>
                    <x-table-cell>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full border {{ $party->party_type == 1 ? 'bg-sky-50 text-sky-700 border-sky-100' : 'bg-violet-50 text-violet-700 border-violet-100' }}">
                            {{ $party->party_type_label }}
                        </span>
                    </x-table-cell>
                    <x-table-cell>
                        @if($party->openingBalances->count() > 0)
                            <div class="space-y-1">
                                @foreach($party->openingBalances as $balance)
                                    <div class="text-sm">
                                        <span class="font-medium">{{ $balance->currency->currency_symbol }}</span>
                                        {{ number_format($balance->opening_balance, 2) }}
                                        <span class="text-xs {{ $balance->entry_type == 1 ? 'text-green-600' : 'text-red-600' }}">
                                            ({{ $balance->entry_type == 1 ? 'CR' : 'DR' }})
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400">No balance</span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full border {{ $party->status == 1 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                            {{ $party->status_label }}
                        </span>
                    </x-table-cell>
                </tr>
            @empty
                <tr>
                    <x-table-cell colspan="6" class="text-center text-gray-500">No parties found</x-table-cell>
                </tr>
            @endforelse
        </tbody>
    </x-table-wrapper>

    <div class="mt-4">
        {{ $parties->links() }}
    </div>
</x-app-layout>
