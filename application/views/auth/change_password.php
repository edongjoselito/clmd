<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password - CLMD Region XI</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { min-height:100vh; background: linear-gradient(135deg,#0a3d62 0%, #1e6091 100%); display:flex; align-items:center; justify-content:center; }
  .login-card { width: 100%; max-width: 450px; border:none; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
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
    <i class="bi bi-shield-lock-fill" style="font-size:2.5rem; color:#0a3d62;"></i>
    <h4 class="mt-2">Change Password</h4>
    <small>CLMD - DepEd Region XI</small>
  </div>
  <div class="login-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>
    <?php if ($msg = $this->session->flashdata('error')): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = $this->session->flashdata('success')): ?>
      <div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
        <small class="text-muted">Minimum 8 characters</small>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <a href="<?= site_url('dashboard') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-shield-check"></i> Change Password
      </button>
    </form>
  </div>
</div>
</body>
</html>
