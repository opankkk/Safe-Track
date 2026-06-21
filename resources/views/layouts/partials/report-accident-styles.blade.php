@once
@push('styles')
<style>
  :root{
    --hse-primary:#2d6cdf;
    --hse-accent:#6f42c1;
    --hse-bg:#f4f6f9;
    --hse-card:#ffffff;
    --hse-text:#343a40;
    --hse-muted:#6c757d;
  }

  .public-page{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(45,108,223,.18), transparent 55%),
      radial-gradient(900px 500px at 90% 0%, rgba(111,66,193,.18), transparent 55%),
      var(--hse-bg);
    padding: 28px 0 42px;
  }

  .menu-card{ cursor:pointer; }

  .menu-card .small-box{
    border-radius:16px;
    box-shadow:0 14px 38px rgba(0,0,0,.08);
    transition:.2s ease;
    overflow:hidden;
    min-height:150px;
    display:flex;
    flex-direction:column;
  }

  .menu-card .small-box:hover{
    transform:translateY(-4px);
    box-shadow:0 20px 45px rgba(0,0,0,.12);
  }

  .menu-card .small-box .inner p{
    margin-bottom:0;
    opacity:.95;
  }

  .menu-card .small-box-footer{
    border-bottom-left-radius:16px;
    border-bottom-right-radius:16px;
    margin-top:auto;
  }

  .modal-content{
    border-radius:16px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 20px 55px rgba(0,0,0,.18);
    overflow:hidden;
  }

  .modal-header{
    background:linear-gradient(135deg, rgba(45,108,223,.10), rgba(111,66,193,.10));
    border-bottom:1px solid rgba(0,0,0,.06);
  }

  .modal-title{ font-weight:900; }

  /* Hero header */
  .hero{
    background: linear-gradient(135deg, rgba(45,108,223,.12), rgba(111,66,193,.12));
    border:1px solid rgba(0,0,0,.06);
    border-radius: 14px;
    padding: 16px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    box-shadow: 0 8px 24px rgba(0,0,0,.04);
  }

  .hero-left{ display:flex; gap:12px; align-items:center; }

  .hero-badge{
    width:44px;height:44px;border-radius:12px;
    display:grid;place-items:center;
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    color:#fff;
    box-shadow: 0 12px 18px rgba(45,108,223,.25);
  }

  .hero-title{ font-weight:900; font-size:18px; margin:0; color:var(--hse-text); }
  .hero-sub{ margin:0; color:var(--hse-muted); font-size:.9rem; }

  /* Stepper */
  .stepper{
    margin-top:14px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }

  .step{
    flex:1 1 170px;
    background:rgba(255,255,255,.82);
    border:1px solid rgba(0,0,0,.06);
    border-radius:12px;
    padding:10px 12px;
    display:flex;
    align-items:center;
    gap:10px;
    transition:.18s ease;
  }

  .step:hover{
    transform:translateY(-1px);
    box-shadow:0 10px 22px rgba(0,0,0,.05);
  }

  .step-dot{
    width:30px;height:30px;border-radius:11px;
    display:grid;place-items:center;
    background:rgba(45,108,223,.12);
    color:var(--hse-primary);
    font-weight:900;
  }

  .step-text b{ display:block; font-size:.92rem; color:var(--hse-text); }
  .step-text small{ color:var(--hse-muted); }

  /* Card wrapper */
  .main-card{
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 14px 38px rgba(0,0,0,.06);
    border:1px solid rgba(0,0,0,.06);
    background:var(--hse-card);
  }

  .section-card{
    border-radius:14px;
    overflow:hidden;
    border:1px solid rgba(0,0,0,.06);
    background:var(--hse-card);
  }

  .section-card .card-header{
    background:linear-gradient(135deg, rgba(45,108,223,.08), rgba(111,66,193,.08));
    border-bottom:1px solid rgba(0,0,0,.06);
    padding:14px 16px;
  }

  .modal .section-card{
    margin-bottom:12px;
  }

  .modal .section-card .section-head{
    padding:12px 14px;
    background:linear-gradient(135deg, rgba(45,108,223,.08), rgba(111,66,193,.08));
    border-bottom:1px solid rgba(0,0,0,.06);
  }

  .modal .section-card .section-head .t{
    margin:0;
    font-weight:900;
    color:var(--hse-text);
  }

  .modal .section-card .section-head .s{
    margin:2px 0 0;
    color:var(--hse-muted);
    font-size:.88rem;
  }

  .section-title{
    font-weight:900;
    margin:0;
    color:var(--hse-text);
  }

  .section-hint{
    margin:.25rem 0 0;
    color:var(--hse-muted);
    font-size:.88rem;
  }

  .required::after{
    content:" *";
    color:#dc3545;
    font-weight:900;
  }

  label{ font-weight:800; color:var(--hse-text); }

  .form-control,
  .custom-file-label,
  select.form-control,
  textarea.form-control{
    border-radius:12px;
    border-color:rgba(0,0,0,.12);
  }

  .form-control:focus{
    border-color:rgba(45,108,223,.45);
    box-shadow:0 0 0 .2rem rgba(45,108,223,.12);
  }

  .btn{
    border-radius:12px;
    padding:.55rem .9rem;
    font-weight:800;
  }

  .btn-info-match{
    background-color:#17a2b8;
    border:1px solid #17a2b8;
    color:#fff;
  }

  .btn-info-match:hover{
    background-color:#138496;
    border-color:#117a8b;
  }

  .card-footer{
    background:#fff;
    border-top:1px solid rgba(0,0,0,.06);
  }

  .alert{
    border-radius:12px;
    border:1px solid rgba(0,0,0,.06);
  }

  .text-muted{
    color:var(--hse-muted) !important;
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 767.98px) {
    .public-page {
      padding: 14px 0 24px !important;
    }

    .hero {
      flex-direction: column !important;
      align-items: flex-start !important;
      padding: 12px 14px !important;
    }

    .hero-right {
      align-self: flex-end;
    }

    .stepper {
      gap: 6px !important;
    }

    .step {
      flex: 1 1 calc(50% - 6px) !important;
      min-width: 0 !important;
      padding: 8px 10px !important;
    }

    .step-text b {
      font-size: 0.78rem !important;
    }

    .step-text small {
      font-size: 0.7rem !important;
    }

    .main-card {
      border-radius: 10px !important;
    }

    .section-card {
      border-radius: 10px !important;
    }

    .section-card .card-body {
      padding: 12px !important;
    }

    .card-footer.d-flex {
      flex-direction: column !important;
      gap: 8px !important;
    }

    .card-footer .btn {
      width: 100% !important;
    }
  }

  @media (max-width: 575.98px) {
    .step {
      flex: 1 1 100% !important;
    }

    .hero-title {
      font-size: 15px !important;
    }

    .hero-sub {
      font-size: 0.8rem !important;
    }
  }
</style>
@endpush
@endonce
