<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PPKS Dinsos')</title>
    
     
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    @auth
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white">
            <div class="p-6">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="text-xl font-bold">PPKS Dinsos</div>
                </a>
            </div>

            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 hover:bg-white/10 transition-colors @if(request()->routeIs('dashboard')) bg-white/10 border-l-4 border-white @endif">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('bantuan.index') }}" class="flex items-center px-6 py-3 hover:bg-white/10 transition-colors @if(request()->routeIs('bantuan.*')) bg-white/10 border-l-4 border-white @endif">
                    <i class="fas fa-gift w-5 mr-3"></i>
                    <span>Bantuan</span>
                </a>

                <a href="{{ route('penerima.index') }}" class="flex items-center px-6 py-3 hover:bg-white/10 transition-colors @if(request()->routeIs('penerima.*')) bg-white/10 border-l-4 border-white @endif">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Penerima</span>
                </a>
            </nav>
        </div>

        <!-- Content Wrapper -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">PPKS Dinsos</a>
                    
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span class="hidden md:block">{{ Auth::user()->nama }}</span>
                            <i class="fas fa-user-circle text-xl"></i>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                            <a href="#" onclick="document.getElementById('logoutModal').classList.remove('hidden')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>

            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="text-center text-sm text-gray-600">
                    Copyright &copy; PPKS Dinsos 2025
                </div>
            </footer>
        </div>
    </div>

    <!-- Logout Modal-->
    <div id="logoutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-sign-out-alt text-red-600"></i>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Yakin ingin keluar?</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">Pilih "Logout" jika Anda siap untuk keluar dari sesi Anda.</p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button onclick="document.getElementById('logoutModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none">
                            Batal
                        </button>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    @yield('content')
    @endauth

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button');
            
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <!-- SweetAlert2 for better confirm dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Validation Script -->
    <script src="{{ asset('js/validation.js') }}"></script>
    
    <!-- Form Handler Script -->
    <script src="{{ asset('js/form-handler.js') }}"></script>
    
    <!-- Stack for additional scripts -->
    @stack('scripts')

</body>
</html>