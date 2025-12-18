@extends('layouts.app')

@section('title', 'Dashboard - PPKS Dinsos')

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<!-- Page Heading with Filters -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Dashboard</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500 p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <i class="fas fa-gift text-8xl text-blue-500"></i>
        </div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">Total Bantuan</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalBantuan }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4 animate-pulse">
                <i class="fas fa-gift text-blue-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Penerima Card -->
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500 p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <i class="fas fa-users text-8xl text-green-500"></i>
        </div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-2">Total Penerima</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPenerima }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-4 animate-pulse">
                <i class="fas fa-users text-green-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Bantuan Aktif Card -->
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-cyan-500 p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <i class="fas fa-calendar text-8xl text-cyan-500"></i>
        </div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-xs font-semibold text-cyan-600 uppercase tracking-wide mb-2">Bantuan Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $bantuanAktif }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $totalBantuan > 0 ? ($bantuanAktif / $totalBantuan * 100) : 0 }}%"></div>
                </div>
            </div>
            <div class="bg-cyan-100 rounded-full p-4 animate-pulse">
                <i class="fas fa-calendar text-cyan-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Penerima Bulan Ini Card -->
    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-amber-500 p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <i class="fas fa-user-check text-8xl text-amber-500"></i>
        </div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide mb-2">Penerima Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">{{ $penerimaBulanIni }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('bantuan.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 group">
            <div class="bg-blue-500 text-white rounded-full p-3 mb-2 group-hover:bg-blue-600 transition-colors">
                <i class="fas fa-plus text-lg"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Tambah Bantuan</span>
        </a>
        
        <a href="{{ route('penerima.create') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group">
            <div class="bg-green-500 text-white rounded-full p-3 mb-2 group-hover:bg-green-600 transition-colors">
                <i class="fas fa-user-plus text-lg"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Tambah Penerima</span>
        </a>
        
        <a href="{{ route('bantuan.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group">
            <div class="bg-purple-500 text-white rounded-full p-3 mb-2 group-hover:bg-purple-600 transition-colors">
                <i class="fas fa-list text-lg"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Daftar Bantuan</span>
        </a>
        
        <a href="{{ route('penerima.index') }}" class="flex flex-col items-center justify-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors duration-200 group">
            <div class="bg-orange-500 text-white rounded-full p-3 mb-2 group-hover:bg-orange-600 transition-colors">
                <i class="fas fa-users text-lg"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">Daftar Penerima</span>
        </a>
    </div>
</div>



<div class="grid grid-cols-1 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terkini</h3>
        </div>
        <div class="space-y-3" id="activitiesList">
            @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="bg-{{ $activity['color'] }}-100 rounded-full p-2 mt-1">
                        <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}-500 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-inbox text-2xl mb-2"></i>
                    <p class="text-sm">Belum ada aktivitas terkini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection