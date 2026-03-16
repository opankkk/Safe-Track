<?php

namespace App\Livewire\HSE;

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
    public int $countVerifikasiHasil = 0;

    public array $notifications = [];
    public array $chartData     = [];

    public string $filterYear;

    public function mount(): void
    {
        $this->filterYear  = Carbon::now('Asia/Jakarta')->format('Y');
        $this->loadData();
    }

    public function updatedFilterYear(): void   { $this->loadData(); }

    private function loadData(): void
    {
        $year  = (int) $this->filterYear;

        // METRIC CARDS
        $this->countAccident = Report::where('type', 'accident')
            ->whereYear('created_at',  $year)
            ->count();

        $this->countUnsafeAction = Report::where('type', 'unsafe_action')
            ->whereYear('created_at',  $year)
            ->count();

        $this->countUnsafeCondition = Report::where('type', 'unsafe_condition')
            ->whereYear('created_at',  $year)
            ->count();

        $this->countVerifikasiHasil = Report::whereIn('sub_status', [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE])->count();

        // NOTIFIKASI
        $this->notifications = Report::where(function($q) {
                $q->where('status', 'pending')
                  ->orWhereIn('sub_status', [Report::SUB_REPORT_PENDING_HSE, Report::SUB_REPORT_VERIFICATION_HSE]);
            })
            ->latest('updated_at')
            ->take(10)
            ->get(['id', 'report_number', 'type', 'status', 'sub_status', 'created_at'])
            ->map(fn($r) => [
                'id'            => $r->id,
                'report_number' => $r->report_number,
                'type'          => $r->type,
                'status'        => $r->status,
                'time_diff'     => $r->created_at->diffForHumans(),
                'label'         => $this->typeLabel($r->type),
                'href'          => $this->typeHref($r->type),
                'bg'            => match(true) {
                    str_contains($r->sub_status, 'rejected') => '#dc3545',
                    str_contains($r->sub_status, 'verification') || str_contains($r->sub_status, 'pending') => '#ff851b',
                    default => '#17a2b8'
                },
                'icon'          => match(true) {
                    str_contains($r->sub_status, 'rejected') => 'fas fa-exclamation-circle',
                    str_contains($r->sub_status, 'verification') || str_contains($r->sub_status, 'pending') => 'fas fa-clock',
                    default => 'fas fa-check-circle'
                },
                'status_label'  => Report::subStatusLabel($r->sub_status),
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
            'accident'         => url('/hse/accident'),
            'unsafe_action',
            'unsafe_condition' => url('/hse/incident'),
            default            => '#',
        };
    }

    public function render()
    {
        return view('livewire.h-s-e.dashboard');
    }
}
