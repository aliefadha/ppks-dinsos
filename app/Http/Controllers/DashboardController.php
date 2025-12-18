<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;
use App\Models\Penerima;
use App\Models\BantuanPenerima;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalBantuan = Bantuan::count();
        $totalPenerima = Penerima::count();
        $bantuanAktif = Bantuan::where('tanggal', '>=', now()->subMonth())->count();
        $penerimaBulanIni = Penerima::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();

        // Data for charts
        $bantuanPerBulan = $this->getBantuanPerBulan();
        $penerimaPerBulan = $this->getPenerimaPerBulan();
        $jenisBantuanData = $this->getJenisBantuanData();
        
        // Recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Quick stats
        $totalDistribusi = BantuanPenerima::count();
        $persentasePertumbuhan = $this->calculateGrowthPercentage();

        return view('dashboard.index', compact(
            'totalBantuan',
            'totalPenerima',
            'bantuanAktif',
            'penerimaBulanIni',
            'bantuanPerBulan',
            'penerimaPerBulan',
            'jenisBantuanData',
            'recentActivities',
            'totalDistribusi',
            'persentasePertumbuhan'
        ));
    }

    /**
     * Get bantuan data per month for the last 6 months
     */
    private function getBantuanPerBulan()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Bantuan::whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year)
                            ->count();
            $data[] = [
                'bulan' => $month->format('M'),
                'jumlah' => $count
            ];
        }
        return collect($data);
    }

    /**
     * Get penerima data per month for the last 6 months
     */
    private function getPenerimaPerBulan()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Penerima::whereMonth('created_at', $month->month)
                             ->whereYear('created_at', $month->year)
                             ->count();
            $data[] = [
                'bulan' => $month->format('M'),
                'jumlah' => $count
            ];
        }
        return collect($data);
    }

    /**
     * Get bantuan distribution data by month
     */
    private function getJenisBantuanData()
    {
        // Since there's no 'jenis' column, we'll group by month for distribution
        $data = Bantuan::selectRaw('MONTH(tanggal) as month, COUNT(*) as jumlah')
                     ->whereYear('tanggal', now()->year)
                     ->groupBy('month')
                     ->orderBy('month')
                     ->get()
                     ->map(function ($item) {
                         $monthNames = [
                             1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                             5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                             9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                         ];
                         return [
                             'jenis' => $monthNames[$item->month] ?? 'Unknown',
                             'jumlah' => $item->jumlah
                         ];
                     });
        
        // Convert to collection so we can use pluck() in the view
        return collect($data);
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = [];
        
        // Recent bantuan created
        $recentBantuan = Bantuan::latest()->take(3)->get();
        foreach ($recentBantuan as $bantuan) {
            $activities[] = [
                'type' => 'bantuan',
                'message' => "Bantuan '{$bantuan->nama_bantuan}' ditambahkan",
                'time' => $bantuan->created_at->diffForHumans(),
                'icon' => 'fa-gift',
                'color' => 'blue'
            ];
        }
        
        // Recent penerima created
        $recentPenerima = Penerima::latest()->take(3)->get();
        foreach ($recentPenerima as $penerima) {
            $activities[] = [
                'type' => 'penerima',
                'message' => "Penerima '{$penerima->nama}' didaftarkan",
                'time' => $penerima->created_at->diffForHumans(),
                'icon' => 'fa-user',
                'color' => 'green'
            ];
        }
        
        // Sort by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 5);
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowthPercentage()
    {
        $thisMonth = Penerima::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();
        
        $lastMonth = Penerima::whereMonth('created_at', now()->subMonth()->month)
                             ->whereYear('created_at', now()->subMonth()->year)
                             ->count();
        
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
}