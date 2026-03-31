<x-app-layout>
  @section('title', 'Settings - ExchangeHub')
  <x-breadcrumb :breadcrumbs="[
    ['url' => '/', 'label' => 'Home'],
    ['url' => '#', 'label' => 'Settings'],
]" />

  <x-dynamic-heading title="Settings" />  

  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6 mt-5">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Master Files</h3>
                    <p class="text-xs text-gray-500">Core data and lookup tables</p>
                </div>
            </div>
        </div>
        <div>
            <ul role="list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @can('view countries')
                    <x-setting-link initials="MC" url="{{ url('countries') }}" title="Manage" subtitle="Countries"
                        bgColor="bg-green-500" />
                @endcan

                @can('view cities')
                    <x-setting-link initials="MC" url="{{ url('cities') }}" title="Manage" subtitle="Cities"
                        bgColor="bg-yellow-500" />
                @endcan

                @can('view timezones')
                    <x-setting-link initials="MT" url="{{ url('timezones') }}" title="Manage" subtitle="Timezones"
                        bgColor="bg-purple-600" />
                @endcan

                @can('view currencies')
                    <x-setting-link initials="MC" url="{{ url('currencies') }}" title="Manage" subtitle="Currencies"
                        bgColor="bg-yellow-500" />
                @endcan

               

                @can('view businesses')
                    <x-setting-link initials="MC" url="{{ url('businesses') }}" title="Manage" subtitle="Businesses"
                        bgColor="bg-yellow-800" />
                        @endcan

                        @can('view subusers')
                        <x-setting-link initials="SB" url="{{ url('subusers') }}" title="Manage" subtitle="Sub Users"
                        bgColor="bg-green-500" />
                        @endcan
                        
                        
                        

               

                  


                   <!-- <x-setting-link initials="MB" url="{{ url('banks') }}" title="Manage" subtitle="Banks"
                  bgColor="bg-purple-800" /> -->
               



                

            </ul>
        </div>
    </div>



    <!-- <div class="mb-10 p-10 mt-5 bg-gray-100 py-5 rounded-xl border border-gray-200">
        <h3 class="text-base py-5 font-semibold text-gray-900">Arms Management</h3>
        <div>
            <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                  @can('view arm_types')
                    <x-setting-link initials="AM" url="{{ url('arms-types') }}" title="Arm" subtitle="Types"
                        bgColor="bg-green-800" /> 
                        @endcan

                        @can('view arm_categories')
                        <x-setting-link initials="AC" url="{{ url('arms-categories') }}" title="Arm" subtitle="Categories"
                        bgColor="bg-green-600" /> 
                        @endcan

                        @can('view arm_makes')
                        <x-setting-link initials="AM" url="{{ url('arms-makes') }}" title="Arm" subtitle="Makes"
                        bgColor="bg-green-500" /> 
                        @endcan

                        @can('view arm_calibers')
                        <x-setting-link initials="AC" url="{{ url('arms-calibers') }}" title="Arm" subtitle="Calibers"
                        bgColor="bg-green-900" /> 
                        @endcan

                        @can('view arm_conditions')
                        <x-setting-link initials="AC" url="{{ url('arms-conditions') }}" title="Arm" subtitle="Conditions"
                        bgColor="bg-green-700" /> 
                        @endcan

            </ul>
        </div>
    </div> -->




  @can('view user management')
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <div class="flex items-start justify-between gap-4 mb-4">
      <div class="flex items-center gap-2">
        <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900">User Management</h3>
          <p class="text-xs text-gray-500">Users, roles, and permissions</p>
        </div>
      </div>
    </div>
    <div>
      <ul role="list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @can('view users')
        <x-setting-link
          initials="MU"
          url="{{ url('users')}}"
          title="Manage"
          subtitle="Users"
          bgColor="bg-purple-600"
        />
    @endcan
    
        @can('view roles')
        <x-setting-link
          initials="MR"
          url="{{ url('roles')}}"
          title="Manage"
          subtitle="Roles"
          bgColor="bg-yellow-500"
        />
        @endcan

        @can('view permissions')
        <x-setting-link
          initials="MP"
          url="{{ url('permissions')}}"
          title="Manage"
          subtitle="Permission"
          bgColor="bg-green-500"
        />
        @endcan

      </ul>
    </div>
  </div>
  @endcan
</x-app-layout>
