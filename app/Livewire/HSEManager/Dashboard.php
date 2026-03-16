<?php

namespace App\Livewire\HSEManager;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Report;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public int $countAccident        = 0;
    public int $countUnsafeAction    = 0;
    public int $countUnsafeCondition = 0;

    public array $notifications = [];
    public array $chartData     = [];

    public string $filterYear;

    public function mount(): void
    {
        $this->filterYear  = Carbon::now('Asia/Jakarta')->format('Y');
        $this->loadData();
    }

    public function updatedFilterYear(): void  { $this->loadData(); }

    private function loadData(): void
    {
        $year  = (int) $this->filterYear;

        // METRIC CARDS
        $this->countAccident = Report::where('type', 'accident')
            ->whereYear('created_at', $year)
            ->count();

        $this->countUnsafeAction = Report::where('type', 'unsafe_action')
            ->whereYear('created_at', $year)
            ->count();

        $this->countUnsafeCondition = Report::where('type', 'unsafe_condition')
            ->whereYear('created_at', $year)
            ->count();

        // NOTIFICATIONS
        $pendingPlans = Report::where('sub_status', Report::SUB_PLAN_VERIFICATION)
            ->latest('updated_at')
            ->get(['id', 'report_number', 'type', 'created_at', 'updated_at'])
            ->toBase()
            ->map(fn($r) => [
                'id'            => $r->id,
                'report_number' => $r->report_number,
                'type'          => $r->type,
                'created_at'    => $r->created_at,
                'updated_at'    => $r->updated_at,
                'time_diff'     => Carbon::parse($r->updated_at)->diffForHumans(),
                'label'         => $this->typeLabel($r->type),
                'status_label'  => 'Verifikasi Plan Tindak Lanjut',
                'href'          => url('/hse-manager/plan-tindak-lanjut'),
                'bg'            => '#ff851b',
                'icon'          => 'fas fa-clock',
            ]);

        $pendingReports = Report::whereIn('sub_status', [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_MANAGER])
            ->whereHas('action', fn($q) => $q->where('status', 'pending'))
            ->latest('updated_at')
            ->get(['id', 'report_number', 'type', 'created_at', 'updated_at'])
            ->toBase()
            ->map(fn($r) => [
                'id'            => $r->id,
                'report_number' => $r->report_number,
                'type'          => $r->type,
                'created_at'    => $r->created_at,
                'updated_at'    => $r->updated_at,
                'time_diff'     => Carbon::parse($r->updated_at)->diffForHumans(),
                'label'         => $this->typeLabel($r->type),
                'status_label'  => 'Verifikasi Hasil PIC',
                'href'          => url('/hse-manager/report'),
                'bg'            => '#ff851b',
                'icon'          => 'fas fa-clock',
            ]);

        $this->notifications = $pendingPlans->concat($pendingReports)
            ->sortByDesc('updated_at')
            ->take(15)
            ->toArray();

        // CHART
        $closed = Report::with('accidentDetail')
            ->where('status', 'closed')
            ->whereYear('updated_at', $year)
            ->get(['id', 'type', 'updated_at']);

        $categories = [
            'unsafe_condition', 'unsafe_action',
            'Nearmiss', 'Gangguan Kesehatan', 'First Aid', 'Medical Aid',
            'Heavy Accident', 'Fatality', 'Loss Mandays', 'Property Damage',
        ];

        $months = array_fill(1, 12, array_fill_keys($categories, 0));

        foreach ($closed as $r) {
            $m = (int) Carbon::parse($r->updated_at)->setTimezone('Asia/Jakarta')->format('n');
            if ($r->type === 'unsafe_action') {
                $months[$m]['unsafe_action']++;
            } elseif ($r->type === 'unsafe_condition') {
                $months[$m]['unsafe_condition']++;
            } elseif ($r->type === 'accident') {
                $jenis = $r->accidentDetail->jenis_insiden ?? 'Nearmiss';
                if (in_array($jenis, $categories)) {
                    $months[$m][$jenis]++;
                } else {
                    $months[$m]['Nearmiss']++;
                }
            }
        }

        $this->chartData = [
            'labels'             => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'unsafeCondition'    => array_column(array_values($months), 'unsafe_condition'),
            'unsafeAction'       => array_column(array_values($months), 'unsafe_action'),
            'nearmiss'           => array_column(array_values($months), 'Nearmiss'),
            'gangguanKesehatan'  => array_column(array_values($months), 'Gangguan Kesehatan'),
            'firstAid'           => array_column(array_values($months), 'First Aid'),
            'medicalAid'         => array_column(array_values($months), 'Medical Aid'),
            'heavyAccident'      => array_column(array_values($months), 'Heavy Accident'),
            'fatality'           => array_column(array_values($months), 'Fatality'),
            'lossMandays'        => array_column(array_values($months), 'Loss Mandays'),
            'propertyDamage'     => array_column(array_values($months), 'Property Damage'),
            'hasData'            => $closed->isNotEmpty(),
        ];
        $this->dispatch('dashboardUpdated');
    }

    private function typeLabel(string $type): string
    {
        return match ($type) {
            'accident'         => 'Accident Report',
            'unsafe_action'    => 'Unsafe Action',
            'unsafe_condition' => 'Unsafe Condition',
            default            => 'Laporan',
        };
    }

    private function subStatusLabel(string $sub): string
    {
        return match ($sub) {
            'plan_verification'  => 'Menunggu Approval Plan',
            'report_pending_hse' => 'Report Menunggu HSE',
            default              => 'Pending',
        };
    }

    private function subStatusHref(string $sub): string
    {
        return match ($sub) {
            'plan_verification'  => url('/hse-manager/plan-tindak-lanjut'),
            'report_pending_hse' => url('/hse-manager/report'),
            default              => url('/hse-manager/plan-tindak-lanjut'),
        };
    }

    private function subStatusBg(string $sub): string
    {
        return match ($sub) {
            'plan_verification'  => '#ff851b',
            'report_pending_hse' => '#3c8dbc',
            default              => '#ff851b',
        };
    }

    private function subStatusIcon(string $sub): string
    {
        return match ($sub) {
            'plan_verification'  => 'fas fa-clipboard-check',
            'report_pending_hse' => 'fas fa-file-alt',
            default              => 'fas fa-clock',
        };
    }

    public function render()
    {
        return view('livewire.h-s-e-manager.dashboard');
    }
}
