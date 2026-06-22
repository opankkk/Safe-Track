{{--
  Partial: resources/views/livewire/partials/_workflow-status.blade.php
  
  Props:
    $report  → instance App\Models\Report
    $showNote → bool (default true) — tampilkan ikon 💬 jika ada catatan
--}}

@php
  $sub       = $report->sub_status ?? 'pending_hse';
  $label     = \App\Models\Report::subStatusLabel($sub);
  $badgeClass= \App\Models\Report::subStatusBadgeClass($sub);

  // Catatan: ambil catatan terbaru yang relevan
  $note = null;
  if (in_array($sub, ['plan_rejected_manager'])) {
      $note = $report->plan?->manager_note;
  } elseif (in_array($sub, ['report_rejected_manager'])) {
      $note = $report->action?->manager_note;
  } elseif (in_array($sub, ['pic_working']) && $report->hse_note) {
      $note = $report->hse_note;
  }

  $showNote = $showNote ?? true;
@endphp

<div class="d-flex flex-column align-items-center" style="gap:4px;">
  <span class="badge {{ $badgeClass }} px-2 py-1"
        style="border-radius:.4rem; font-size:10px; font-weight:700; white-space:nowrap; max-width:160px; overflow:hidden; text-overflow:ellipsis; {{ in_array($badgeClass, ['badge-warning', 'badge-info']) ? 'color: #000 !important;' : '' }}"
        title="{{ $label }}">
    {{ $label }}
  </span>

  @if($showNote && $note)
    <button type="button"
            class="btn btn-link btn-sm p-0 text-warning js-show-note"
            style="font-size:12px; line-height:1;"
            title="Lihat Catatan"
            data-note="{{ e($note) }}">
      <i class="fas fa-comment-alt"></i> Catatan
    </button>
  @endif
</div>
