<div>
   <!-- Mobile Sidebar -->
   <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-cloak
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

      <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="sidebarOpen = false"></div>
      
      <div class="fixed inset-0 flex"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full">

         <div class="relative mr-16 flex w-full max-w-xs flex-1">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
               <button type="button" class="-m-2.5 p-2.5 text-gray-500 hover:text-gray-800 transition-colors rounded-full hover:bg-white" @click="sidebarOpen = false">
                  <span class="sr-only">Close sidebar</span>
                  <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
               </button>
            </div>

            <!-- Mobile Content -->
            <div class="flex grow flex-col overflow-hidden bg-white px-4 pb-4 shadow-xl border-r border-gray-200">
               <!-- Logo -->
               <div class="flex h-20 shrink-0 items-center border-b border-gray-200">
                  <a href="{{route('dashboard')}}" class="flex items-center gap-3 group">
                     <div class="relative">
                        <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-amber-500 via-amber-600 to-yellow-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-105 transition-transform duration-300">
                           <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                           </svg>
                        </div>
                     </div>
                     <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">ExchangeHub</h1>
                        <p class="text-xs text-gray-500 font-medium">Currency Platform</p>
                     </div>
                  </a>
               </div>
               
               <!-- Navigation -->
               <nav class="flex flex-1 flex-col min-h-0">
                  <ul role="list" class="flex flex-1 flex-col gap-y-2 min-h-0">
                     <!-- Main -->
                     <li>
                        <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2">Main</div>
                        <ul role="list" class="space-y-1">
                           <li>
                              <a href="{{ route('dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                 </svg>
                                 <span>Dashboard</span>
                                 @if(request()->routeIs('dashboard'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                        </ul>
                     </li>

                     <!-- Management -->
                     <li>
                        <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4">Management</div>
                        <ul role="list" class="space-y-1">
                           @can('view parties')
                           <li>
                              <a href="{{ route('parties.dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('parties.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                 </svg>
                                 <span>Parties</span>
                                 @if(request()->routeIs('parties.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                           @endcan
                           <li>
                              <a href="{{ route('banks.dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('banks.*') && !request()->routeIs('bank-transfers.*') && !request()->routeIs('general-vouchers.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                 </svg>
                                 <span>Banks</span>
                                 @if(request()->routeIs('banks.*') && !request()->routeIs('bank-transfers.*') && !request()->routeIs('general-vouchers.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                           <li>
                              <a href="{{ route('general-vouchers.index') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('general-vouchers.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                 </svg>
                                 <span>General Vouchers</span>
                                 @if(request()->routeIs('general-vouchers.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-white"></span>
                                 @endif
                              </a>
                           </li>
                           <li>
                              <a href="{{ route('purchases.dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('purchases.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                 </svg>
                                 <span>Purchase</span>
                                 @if(request()->routeIs('purchases.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                           <li>
                              <a href="{{ route('sales.dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('sales.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                 </svg>
                                 <span>Sales</span>
                                 @if(request()->routeIs('sales.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                           <li>
                              <a href="{{ route('assets.dashboard') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('assets.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                                 </svg>
                                 <span>Assets</span>
                                 @if(request()->routeIs('assets.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                        </ul>
                     </li>

                     <!-- Reports -->
                     <li>
                        <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4">Reports</div>
                        <ul role="list" class="space-y-1">
                           <li>
                              <a href="{{ route('reports.index') }}"
                                 class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                                 <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                 </svg>
                                 <span>Reports</span>
                                 @if(request()->routeIs('reports.*'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                                 @endif
                              </a>
                           </li>
                        </ul>
                     </li>

                     <!-- Settings -->
                     @can('view settings')
                     <li class="mt-auto pt-4 border-t border-gray-200">
                        <a href="{{ route('settings') }}"
                           class="group flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('settings') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                           <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                           </svg>
                           <span>Settings</span>
                                 @if(request()->routeIs('settings'))
                                 <span class="ml-auto w-2 h-2 rounded-full bg-indigo-500"></span>
                           @endif
                        </a>
                     </li>
                     @endcan
                  </ul>
               </nav>

               <!-- Footer Status -->
               
            </div>
         </div>
      </div>
   </div>

   <!-- Desktop Premium Sidebar - Better than Microsoft 365 -->
   <div class="sidebar-desktop hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col min-h-0" x-cloak>
      
      <div class="flex grow flex-col min-h-0 overflow-hidden bg-white shadow-sm border-r border-gray-200">
         
         <!-- Logo Section with Animation -->
         <div class="flex shrink-0 items-center border-b border-gray-200 py-5 transition-[padding] duration-300"
              :class="sidebarCollapsed ? 'justify-center px-2' : 'px-4'">
            <a href="{{route('dashboard')}}" 
               class="flex items-center gap-3 group w-full overflow-hidden"
               :class="sidebarCollapsed ? 'justify-center' : ''">
               <div class="relative flex-shrink-0">
                  <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-amber-500 via-amber-600 to-yellow-600 flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/50 group-hover:scale-110 transition-all duration-300">
                     <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                     </svg>
                  </div>
               </div>
               
               <div 
                  class="flex-1 min-w-0 transition-all duration-300"
                  x-show="!sidebarCollapsed"
                  x-transition:enter="transition ease-out duration-300 delay-75"
                  x-transition:enter-start="opacity-0 -translate-x-2"
                  x-transition:enter-end="opacity-100 translate-x-0"
                  x-transition:leave="transition ease-in duration-200"
                  x-transition:leave-start="opacity-100"
                  x-transition:leave-end="opacity-0">
                  <h1 class="text-lg font-bold text-gray-900 tracking-tight">Exchange 365</h1>
                  <p class="text-xs text-gray-500 font-medium">Currency Platform</p>
               </div>
            </a>
         </div>

         <!-- Navigation with sections (Main, Management, Reports, Settings) -->
         <nav class="flex flex-1 flex-col min-h-0 px-2 py-4">
            <ul role="list" class="flex flex-1 flex-col min-h-0 gap-y-2">
              <!-- Main -->
              <li>
                 <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2 transition-all duration-300 overflow-hidden"
                      :class="sidebarCollapsed ? 'w-0 opacity-0 h-0' : 'w-auto opacity-100'">
                    Main
                 </div>
                 <ul role="list" class="space-y-1">
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                             @if(request()->routeIs('dashboard'))
                             <div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-indigo-500" x-show="!sidebarCollapsed"></div>
                             @endif
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Dashboard</div>
                          </a>
                       </div>
                    </li>
                 </ul>
              </li>

              <!-- Management -->
              <li>
                 <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4 transition-all duration-300 overflow-hidden"
                      :class="sidebarCollapsed ? 'w-0 opacity-0 h-0' : 'w-auto opacity-100'">
                    Management
                 </div>
                 <ul role="list" class="space-y-1">
                    @can('view parties')
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('parties.dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('parties.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Parties</span>
                             @if(request()->routeIs('parties.*'))
                             <div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-indigo-500" x-show="!sidebarCollapsed"></div>
                             @endif
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Parties</div>
                          </a>
                       </div>
                    </li>
                    @endcan
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('banks.dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('banks.*') && !request()->routeIs('bank-transfers.*') && !request()->routeIs('general-vouchers.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Banks</span>
                             @if(request()->routeIs('banks.*') && !request()->routeIs('bank-transfers.*') && !request()->routeIs('general-vouchers.*'))
                             <div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-indigo-500" x-show="!sidebarCollapsed"></div>
                             @endif
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Banks</div>
                          </a>
                       </div>
                    </li>
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('general-vouchers.index') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('general-vouchers.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">General Vouchers</span>
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">General Vouchers</div>
                          </a>
                       </div>
                    </li>
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('purchases.dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('purchases.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Purchase</span>
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Purchase</div>
                          </a>
                       </div>
                    </li>
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('sales.dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('sales.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Sales</span>
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Sales</div>
                          </a>
                       </div>
                    </li>
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('assets.dashboard') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('assets.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Assets</span>
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Assets</div>
                          </a>
                       </div>
                    </li>
                 </ul>
              </li>

              <!-- Reports -->
              <li>
                 <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4 transition-all duration-300 overflow-hidden"
                      :class="sidebarCollapsed ? 'w-0 opacity-0 h-0' : 'w-auto opacity-100'">
                    Reports
                 </div>
                 <ul role="list" class="space-y-1">
                    <li>
                       <div x-data="{ tooltip: false }">
                          <a href="{{ route('reports.index') }}"
                             @mouseenter="tooltip = sidebarCollapsed"
                             @mouseleave="tooltip = false"
                             class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('reports.*') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                             <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                             </svg>
                             <span x-show="!sidebarCollapsed" class="truncate">Reports</span>
                             @if(request()->routeIs('reports.*'))
                             <div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-indigo-500" x-show="!sidebarCollapsed"></div>
                             @endif
                             <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Reports</div>
                          </a>
                       </div>
                    </li>
                 </ul>
              </li>

              <!-- Settings -->
              @can('view settings')
              <li class="mt-auto pt-4 border-t border-gray-200">
                 <div x-data="{ tooltip: false }">
                    <a href="{{ route('settings') }}"
                       @mouseenter="tooltip = sidebarCollapsed"
                       @mouseleave="tooltip = false"
                       class="group relative flex items-center gap-3 rounded-md px-3 py-2 text-sm font-semibold {{ request()->routeIs('settings') ? 'bg-white text-gray-900 shadow-sm border border-gray-200' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                       <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                         <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                       </svg>
                       <span x-show="!sidebarCollapsed" class="truncate">Settings</span>
                       @if(request()->routeIs('settings'))
                       <div class="absolute right-3 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-indigo-500" x-show="!sidebarCollapsed"></div>
                       @endif
                       <div x-show="tooltip" x-transition class="tooltip-arrow absolute left-full ml-6 px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg shadow-2xl whitespace-nowrap z-[60] border border-gray-700">Settings</div>
                    </a>
                 </div>
              </li>
              @endcan
            </ul>
         </nav>

         <!-- Footer Status - Premium Design -->
         
      </div>
   </div>
</div>
