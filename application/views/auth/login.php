<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - CLMD Region XI</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { min-height:100vh; background: linear-gradient(135deg,#0a3d62 0%, #1e6091 100%); display:flex; align-items:center; justify-content:center; }
  .login-card { width: 100%; max-width: 420px; border:none; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
  .brand-bar { background:#f5b342; height: 6px; border-radius: 14px 14px 0 0; }
  .login-header { padding: 24px 28px 0; }
  .login-header h4 { color:#0a3d62; font-weight:700; margin:0; }
  .login-header small { color:#6b7c93; }
  .login-body { padding: 22px 28px 28px; }
  .form-control:focus { border-color:#0a3d62; box-shadow: 0 0 0 .2rem rgba(10,61,98,.15); }
  .btn-primary { background:#0a3d62; border-color:#0a3d62; }
  .btn-primary:hover { background:#082c47; border-color:#082c47; }
</style>
</head>
<body>
<div class="card login-card">
  <div class="brand-bar"></div>
  <div class="login-header text-center">
    <i class="bi bi-mortarboard-fill" style="font-size:2.5rem; color:#0a3d62;"></i>
    <h4 class="mt-2">DepEd XI CLMD SELECTS <i class="bi bi-info-circle" style="font-size:0.8rem; cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="R XI-SELECTS (Region XI – Specialization Evaluation, Localization, E-Certification, and Tracking System) is a comprehensive digital platform designed to streamline the evaluation, localization, certification, and monitoring of specialization programs across DepEd Region XI."></i></h4>
    <small>Ensuring Compliance. Expanding Access. Inspiring Excellence.</small>
  </div>
  <div class="login-body">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>" autocomplete="off">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="username" class="form-control" required
               value="<?= htmlspecialchars($username) ?>" autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>
    </form>
    <div class="text-center mt-3 small text-muted">
      &copy; <?= date('Y') ?> DepEd Region XI - CLMD
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const tooltipTriggerElement = document.querySelector('[data-bs-toggle="tooltip"]');
  if (tooltipTriggerElement) {
    new bootstrap.Tooltip(tooltipTriggerElement);
  }
</script>
</body>
</html>
