<?php

namespace App\Livewire\PIC;

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

    public function updatedFilterYear(): void { $this->loadData(); }

    private function loadData(): void
    {
        $year  = (int) $this->filterYear;
        $picId = auth()->id();

        $picStatuses = [
            Report::SUB_WAITING_PIC,
            Report::SUB_PIC_WORKING,
            Report::SUB_REPORT_REJECTED_MANAGER,
            Report::SUB_REPORT_REJECTED_HSE
        ];

        // METRIC CARDS
        $this->countAccident = Report::where('type', 'accident')
            ->whereIn('sub_status', $picStatuses)
            ->whereYear('updated_at', $year)
            ->count();

        $this->countUnsafeAction = Report::where('type', 'unsafe_action')
            ->whereIn('sub_status', $picStatuses)
            ->whereYear('updated_at', $year)
            ->count();

        $this->countUnsafeCondition = Report::where('type', 'unsafe_condition')
            ->whereIn('sub_status', $picStatuses)
            ->whereYear('updated_at', $year)
            ->count();

        // NOTIFIKASI:
        $this->notifications = Report::whereIn('sub_status', [
                'waiting_pic', 'plan_rejected_manager', 'plan_approved_manager',
                'pic_working', 'report_rejected_manager', 'report_rejected_hse',
            ])
            ->latest()
            ->take(10)
            ->get(['id', 'report_number', 'type', 'sub_status', 'created_at'])
            ->map(fn($r) => [
                'id'            => $r->id,
                'report_number' => $r->report_number,
                'type'          => $r->type,
                'sub_status'    => $r->sub_status,
                'time_diff'     => $r->created_at->diffForHumans(),
                'label'         => $this->typeLabel($r->type),
                'sub_label'     => $this->subStatusLabel($r->sub_status),
                'href'          => $this->typeHref($r->type),
                'bg'            => $this->subStatusBg($r->sub_status),
                'icon'          => $this->subStatusIcon($r->sub_status),
            ])
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

    private function typeHref(string $type): string
    {
        return match ($type) {
            'accident'         => url('/pic/accident'),
            'unsafe_action',
            'unsafe_condition' => url('/pic/incident'),
            default            => '#',
        };
    }

    private function subStatusLabel(string $sub): string
    {
        return match ($sub) {
            'waiting_pic'            => 'Menunggu Upload Plan',
            'plan_rejected_manager'  => 'Plan Ditolak Manager',
            'plan_approved_manager'  => 'Siap Dikerjakan',
            'pic_working'            => 'Dalam Pengerjaan',
            'report_rejected_manager'=> 'Report Ditolak Manager',
            'report_rejected_hse'    => 'Report Ditolak HSE',
            default                  => 'Pending',
        };
    }

    private function subStatusBg(string $sub): string
    {
        if (str_contains($sub, 'rejected')) {
            return '#dc3545'; // Red for rejections
        }
        
        return match (true) {
            str_contains($sub, 'waiting') || str_contains($sub, 'working') || str_contains($sub, 'verification') => '#ff851b',
            str_contains($sub, 'approved') || $sub === 'closed' => '#17a2b8',
            default => '#ff851b'
        };
    }

    private function subStatusIcon(string $sub): string
    {
        return match (true) {
            str_contains($sub, 'rejected') => 'fas fa-exclamation-circle',
            str_contains($sub, 'waiting') || str_contains($sub, 'working') || str_contains($sub, 'verification') => 'fas fa-clock',
            str_contains($sub, 'approved') || $sub === 'closed' => 'fas fa-check-circle',
            default => 'fas fa-clock'
        };
    }

    public function render()
    {
        return view('livewire.p-i-c.dashboard');
    }
}
