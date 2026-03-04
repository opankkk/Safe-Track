@once
@push('styles')
<style>
  :root{
    --hse-primary:#2d6cdf;
    --hse-accent:#6f42c1;
    --hse-bg:#f4f6f9;
    --hse-text:#343a40;
    --hse-muted:#6c757d;
  }

  .public-page{
    min-height:100vh;
    background:
      radial-gradient(1200px 600px at 10% -10%, rgba(45,108,223,.18), transparent 55%),
      radial-gradient(900px 500px at 90% 0%, rgba(111,66,193,.18), transparent 55%),
      var(--hse-bg);
    padding: 30px 0 42px;
  }

  .hero{
    background: linear-gradient(135deg, rgba(45,108,223,.12), rgba(111,66,193,.12));
    border:1px solid rgba(0,0,0,.06);
    border-radius:14px;
    padding:16px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    box-shadow:0 8px 24px rgba(0,0,0,.04);
    margin-bottom:14px;
  }

  .hero-left{ display:flex; gap:12px; align-items:center; }

  .hero-badge{
    width:44px;height:44px;border-radius:12px;
    display:grid;place-items:center;
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    color:#fff;
    box-shadow:0 12px 18px rgba(45,108,223,.25);
  }

  .hero-title{ font-weight:900; font-size:18px; margin:0; color:var(--hse-text); }
  .hero-sub{ margin:0; color:var(--hse-muted); font-size:.9rem; }

  .menu-card{ cursor:pointer; }

  .menu-card .small-box{
    border-radius:16px;
    box-shadow:0 14px 38px rgba(0,0,0,.08);
    transition:.2s ease;
    overflow:hidden;
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
  }

  /* Modal */
  .modal-content{
    border-radius:16px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 20px 55px rgba(0,0,0,.18);
    overflow:hidden;
  }

  .modal-header{
    background: linear-gradient(135deg, rgba(45,108,223,.10), rgba(111,66,193,.10));
    border-bottom:1px solid rgba(0,0,0,.06);
  }

  .modal-title{ font-weight:900; }

  /* Form */
  label{ font-weight:800; color:var(--hse-text); }
  .required::after{ content:" *"; color:#dc3545; font-weight:900; }

  .form-control, .custom-file-label, select.form-control, textarea.form-control{
    border-radius:12px;
    border-color: rgba(0,0,0,.12);
  }

  .form-control:focus, select.form-control:focus, textarea.form-control:focus{
    border-color: rgba(45,108,223,.45);
    box-shadow:0 0 0 .2rem rgba(45,108,223,.12);
  }

  .section-card{
    border-radius:14px;
    border:1px solid rgba(0,0,0,.06);
    overflow:hidden;
    background:#fff;
    margin-bottom:12px;
  }

  .section-card .section-head{
    padding:12px 14px;
    background: linear-gradient(135deg, rgba(45,108,223,.07), rgba(111,66,193,.07));
    border-bottom:1px solid rgba(0,0,0,.06);
  }

  .section-card .section-head .t{
    margin:0;
    font-weight:900;
    color:var(--hse-text);
  }

  .section-card .section-head .s{
    margin:2px 0 0;
    color:var(--hse-muted);
    font-size:.88rem;
  }

  .btn{
    border-radius:12px;
    font-weight:800;
    padding:.55rem .9rem;
  }

  .btn-gradient{
    background: linear-gradient(135deg, var(--hse-primary), var(--hse-accent));
    border:none;
    color:#fff;
  }

  .btn-gradient:hover{
    filter:brightness(.98);
    color:#fff;
  }

  .hint{
    color:var(--hse-muted);
    font-size:.86rem;
  }

  /* tombol warna sama seperti bg-info (Unsafe Condition) */
  .btn-info-match{
    background-color:#17a2b8;
    border:1px solid #17a2b8;
    color:#fff;
  }

  .btn-info-match:hover{
    background-color:#138496;
    border-color:#117a8b;
    color:#fff;
  }

  .btn-info-match:focus{
    box-shadow:0 0 0 .2rem rgba(23, 162, 184, .25);
  }
</style>
@endpush
@endonce