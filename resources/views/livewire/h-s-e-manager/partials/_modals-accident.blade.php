{{-- Modal Follow Up Plan --}}
<div class="modal fade" id="modalFollowUpPlan" data-backdrop="static" tabindex="-1" role="dialog" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Setujui Plan Tindak Lanjut</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form wire:submit.prevent="submitApprovePlan">
        <div class="modal-body">
          <div class="form-group">
            <label>Catatan Manager <span class="text-danger">*</span></label>
            <textarea class="form-control" wire:model="managerNote" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm">Simpan Persetujuan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Reject Plan --}}
<div class="modal fade" id="modalRejectPlan" data-backdrop="static" tabindex="-1" role="dialog" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title text-white">Tolak Plan Tindak Lanjut</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <form wire:submit.prevent="submitRejectPlan">
        <div class="modal-body">
          <div class="form-group">
            <label>Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" wire:model="managerRejectNote" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm">Tolak Plan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Reject Report/Result --}}
<div class="modal fade" id="modalRejectReport" data-backdrop="static" tabindex="-1" role="dialog" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title text-white">Tolak Hasil Tindak Lanjut</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <form wire:submit.prevent="submitRejectResult">
        <div class="modal-body">
          <div class="form-group">
            <label>Catatan Penolakan Hasil <span class="text-danger">*</span></label>
            <textarea class="form-control" wire:model="managerRejectNote" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm">Tolak Hasil</button>
        </div>
      </form>
    </div>
  </div>
</div>
