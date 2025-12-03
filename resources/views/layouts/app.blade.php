<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{
          isLoading: false,
          init() {
              // Écouter les événements Livewire
              Livewire.on('show-loading', () => {
                  this.isLoading = true;
              });
              
              Livewire.on('hide-loading', () => {
                  this.isLoading = false;
              });
              
              // Détecter les requêtes Livewire
              Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
                  this.isLoading = true;
                  succeed((response) => {
                      this.isLoading = false;
                      return response;
                  });
                  fail((response) => {
                      this.isLoading = false;
                      return response;
                  });
              });
              
              // Détecter la navigation SPA de Livewire
              Livewire.hook('navigate.start', () => {
                  this.isLoading = true;
              });
              
              Livewire.hook('navigate.finish', () => {
                  setTimeout(() => {
                      this.isLoading = false;
                  }, 300);
              });
          }
      }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Scripts / Styles principaux -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles personnalisés -->
    <style>
        /* Styles pour l'overlay de chargement */
        .loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(17, 24, 39, 0.9);
            backdrop-filter: blur(4px);
        }
        
        .loading-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loading-spinner {
            width: 3.5rem;
            height: 3.5rem;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s linear infinite;
        }
        
        .loading-spinner-secondary {
            width: 2.5rem;
            height: 2.5rem;
            border: 3px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            border-top-color: #8b5cf6;
            animation: spin 1s linear infinite reverse;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Désactiver l'interaction pendant le chargement */
        body.loading {
            pointer-events: none;
            cursor: wait;
        }
        
        body.loading main {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        
        /* Animation de la barre de progression */
        .animate-progress {
            animation: progress 2s ease-in-out infinite;
            background-size: 200% 100%;
            background-image: linear-gradient(90deg, #3b82f6, #8b5cf6, #3b82f6);
        }
        
        @keyframes progress {
            0% {
                width: 0%;
                background-position: 0% 0%;
            }
            50% {
                width: 100%;
                background-position: 200% 0%;
            }
            100% {
                width: 0%;
                background-position: 400% 0%;
            }
        }
        
        .animation-delay-150 {
            animation-delay: 0.15s;
        }
        
        .animation-delay-300 {
            animation-delay: 0.3s;
        }
        
        /* Transition pour le contenu */
        .content-transition {
            transition: opacity 0.3s ease;
        }
    </style>
    
    @yield('styles')
</head>
<body class="font-sans antialiased" :class="{ 'loading': isLoading }">
    <!-- Overlay de chargement -->
    <div x-show="isLoading" 
         x-transition:enter="transition-opacity duration-300"
         x-transition:leave="transition-opacity duration-300"
         class="loading-overlay"
         :class="{ 'active': isLoading }">
        
        <div class="text-center">
            <!-- Spinner principal -->
            <div class="relative mx-auto mb-6">
                <div class="loading-spinner"></div>
                <div class="loading-spinner-secondary"></div>
            </div>
            
            <!-- Logo de l'application -->
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">StockMaster</h2>
            </div>
            
            <!-- Texte de chargement -->
            <p class="text-lg font-medium text-white mb-2">Chargement en cours</p>
            <p class="text-gray-300 text-sm">
                <span class="inline-block animate-bounce">.</span>
                <span class="inline-block animate-bounce animation-delay-150">.</span>
                <span class="inline-block animate-bounce animation-delay-300">.</span>
            </p>
            
            <!-- Barre de progression -->
            <div class="w-64 h-1 bg-gray-700 rounded-full overflow-hidden mt-6 mx-auto">
                <div class="h-full animate-progress"></div>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="min-h-screen bg-gray-100 content-transition" :class="{ 'opacity-50': isLoading }">
        <!-- Navigation Livewire -->
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="content-transition" :class="{ 'opacity-50': isLoading }">
            <!-- Messages flash -->
            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if(session($msg))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="rounded-md px-4 py-3 mb-4
                            {{ $msg === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : '' }}
                            {{ $msg === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : '' }}
                            {{ $msg === 'warning' ? 'bg-yellow-100 border border-yellow-400 text-yellow-700' : '' }}
                            {{ $msg === 'info' ? 'bg-blue-100 border border-blue-400 text-blue-700' : '' }}"
                            role="alert">
                            {{ session($msg) }}
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-current" role="button" onclick="this.parentElement.parentElement.remove()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 0 0-1.414 0L10 8.586 7.066 5.652a1 1 0 1 0-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 1 0 1.414 1.414L10 11.414l2.934 2.934a1 1 0 0 0 1.414-1.414L11.414 10l2.934-2.934a1 1 0 0 0 0-1.414z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </main>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts personnalisés -->
    <script>
        // Fonctions globales pour contrôler le loading
        window.showLoading = function(message = null) {
            Alpine.store('isLoading', true);
            if (message) {
                // Vous pouvez mettre à jour un message spécifique ici
                console.log('Loading message:', message);
            }
        };
        
        window.hideLoading = function() {
            Alpine.store('isLoading', false);
        };
        
        // Initialiser le store Alpine
        document.addEventListener('alpine:init', () => {
            Alpine.store('isLoading', false);
        });
        
        // Détecter le chargement initial
        document.addEventListener('DOMContentLoaded', function() {
            // Cacher le loading initial après un court délai
            setTimeout(() => {
                if (Alpine.store('isLoading')) {
                    Alpine.store('isLoading', false);
                }
            }, 500);
        });
    </script>
    
    @yield('scripts')
</body>
</html>