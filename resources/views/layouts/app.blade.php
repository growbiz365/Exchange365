<!DOCTYPE html>
<html class="h-full antialiased" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ExchangeHub')</title>

    <!-- FOUC Prevention -->
    <script>
        (function() {
            const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            document.documentElement.classList.add(collapsed ? 'sidebar-collapsed' : 'sidebar-expanded');
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Premium Sidebar Width Management with FIXED positioning */
        .sidebar-desktop {
            transition: width 350ms cubic-bezier(0.4, 0, 0.2, 1);
            will-change: width;
        }
        
        html.sidebar-collapsed .sidebar-desktop {
            width: 4.5rem !important;
        }
        
        html.sidebar-expanded .sidebar-desktop {
            width: 18rem !important;
        }

        /* Layout Container - Prevents content jump */
        .layout-container {
            transition: padding-left 350ms cubic-bezier(0.4, 0, 0.2, 1);
            will-change: padding-left;
        }
        
        html.sidebar-collapsed .layout-container {
            padding-left: 4.5rem;
        }
        
        html.sidebar-expanded .layout-container {
            padding-left: 18rem;
        }
        
        @media (max-width: 1024px) {
            html.sidebar-collapsed .layout-container,
            html.sidebar-expanded .layout-container {
                padding-left: 0 !important;
            }
        }

        /* Premium Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }
            100% {
                background-position: 200% center;
            }
        }

        .nav-item-shimmer {
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.03) 50%,
                transparent 100%
            );
            background-size: 200% 100%;
        }

        .nav-item-shimmer:hover {
            animation: shimmer 2s ease-in-out infinite;
        }

        /* Custom Scrollbar - Premium */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgb(15 23 42 / 0.05);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgb(148 163 184 / 0.4), rgb(148 163 184 / 0.6));
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgb(148 163 184 / 0.6), rgb(148 163 184 / 0.8));
        }

        /* Alpine Cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Tooltip Arrow */
        .tooltip-arrow::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: rgb(30 41 59);
        }

        /* Premium Focus States */
        .focus-ring:focus-visible {
            outline: 2px solid rgb(251 191 36);
            outline-offset: 2px;
            border-radius: 0.5rem;
        }

        /* Smooth Page Transitions */
        .page-transition {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-blue-50 overflow-x-hidden"
      x-data="{
          sidebarOpen: false,
          profileMenuOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
              document.documentElement.classList.toggle('sidebar-collapsed', this.sidebarCollapsed);
              document.documentElement.classList.toggle('sidebar-expanded', !this.sidebarCollapsed);
          }
      }"
      x-init="
          $watch('sidebarCollapsed', value => {
              localStorage.setItem('sidebarCollapsed', value);
              document.documentElement.classList.toggle('sidebar-collapsed', value);
              document.documentElement.classList.toggle('sidebar-expanded', !value);
          });
          
          // Keyboard shortcut: Ctrl+B or Cmd+B
          document.addEventListener('keydown', (e) => {
              if ((e.metaKey || e.ctrlKey) && e.key === 'b') {
                  e.preventDefault();
                  toggleSidebar();
              }
          });
      ">

    @include('layouts.sidebar')
    
    <!-- Main Layout Container - Prevents content jump -->
    <div class="layout-container min-h-screen">
        @include('layouts.header')
        
        <main class="py-6 page-transition">
            <div class="px-4 sm:px-6 lg:px-8">
                {{$slot}}
            </div>
        </main>
    </div>

</body>
</html>
