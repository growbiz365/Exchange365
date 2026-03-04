<x-app-layout>
    @section('title', 'Activity Log - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('reports.index'), 'label' => 'Reports Dashboard'],
        ['url' => '#', 'label' => 'Activity Log'],
    ]" />

    <!-- Header -->
    <div class="relative bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 rounded-2xl shadow-xl border border-slate-600/20 p-8 mb-8 mt-4 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-400/20 rounded-full blur-3xl"></div>
        </div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20">
                    <svg class="h-8 w-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Activity Log</h1>
                    <p class="text-sm text-white/80 mt-1">Audit trail of create, update, and delete actions across the system</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/10 text-white border border-white/20">Who, what, when</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-900 bg-white/90 hover:bg-white rounded-xl border border-white/30 shadow-lg transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('reports.activity-log') }}" class="space-y-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="min-w-[140px]">
                    <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1">From date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="min-w-[140px]">
                    <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1">To date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="min-w-[160px]">
                    <label for="event" class="block text-xs font-medium text-gray-600 mb-1">Event</label>
                    <select id="event" name="event" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        <option value="">All events</option>
                        <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label for="log_name" class="block text-xs font-medium text-gray-600 mb-1">Log type</label>
                    <select id="log_name" name="log_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        <option value="">All logs</option>
                        <option value="default" {{ request('log_name') === 'default' ? 'selected' : '' }}>Model changes</option>
                        <option value="auth" {{ request('log_name') === 'auth' ? 'selected' : '' }}>Login / Logout</option>
                        <option value="session" {{ request('log_name') === 'session' ? 'selected' : '' }}>Business switch</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('reports.activity-log') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Activity table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Recent activity
                </h2>
                <span class="text-sm text-gray-500">{{ $activities->total() }} {{ Str::plural('record', $activities->total()) }}</span>
            </div>
        </div>

        @if($activities->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-600 font-medium">No activity recorded yet</p>
                <p class="text-sm text-gray-500 mt-1">Actions on banks, parties, sales, and other records will appear here.</p>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center mt-4 text-sm font-semibold text-slate-600 hover:text-slate-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date &amp; time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="font-medium text-gray-900">{{ $activity->created_at->format('M d, Y') }}</span>
                                    <span class="block text-xs text-gray-500">{{ $activity->created_at->format('g:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity->causer)
                                        <span class="text-sm font-medium text-gray-900">{{ $activity->causer->name ?? $activity->causer->email ?? 'User #' . $activity->causer_id }}</span>
                                        @if(isset($activity->causer->email))
                                            <span class="block text-xs text-gray-500">{{ $activity->causer->email }}</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $eventColors = [
                                            'created' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'updated' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'deleted' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                        $color = $eventColors[$activity->event ?? ''] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        $eventLabel = $activity->event ?? ($activity->log_name === 'auth' ? 'auth' : ($activity->log_name === 'session' ? 'session' : '—'));
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $color }}">
                                        {{ $eventLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                                    {{ $activity->description ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($activity->subject)
                                        @php
                                            $subject = $activity->subject;
                                            $label = class_basename($subject);
                                            if (method_exists($subject, 'getKey')) {
                                                $label .= ' #' . $subject->getKey();
                                            }
                                            if (isset($subject->name)) {
                                                $label .= ' · ' . Str::limit($subject->name, 24);
                                            } elseif (isset($subject->business_name)) {
                                                $label .= ' · ' . Str::limit($subject->business_name, 24);
                                            } elseif (isset($subject->party_name)) {
                                                $label .= ' · ' . Str::limit($subject->party_name, 24);
                                            } elseif (isset($subject->bank_name)) {
                                                $label .= ' · ' . Str::limit($subject->bank_name, 24);
                                            } elseif (isset($subject->asset_name)) {
                                                $label .= ' · ' . Str::limit($subject->asset_name, 24);
                                            }
                                        @endphp
                                        <span class="font-medium text-gray-900">{{ $label }}</span>
                                    @elseif($activity->subject_type)
                                        <span class="text-gray-500">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
