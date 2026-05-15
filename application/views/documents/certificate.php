<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certification - <?= htmlspecialchars($row['control_no']) ?></title>
<style>
  @page { size: A4; margin: 20mm 20mm 22mm 20mm; }
  * { box-sizing: border-box; }
  body { font-family: 'Times New Roman', Times, serif; color:#222; margin:0; padding:0; background:#fff; }
  .page { width: 210mm; min-height: 297mm; padding: 18mm 20mm; margin: 0 auto; background:#fff; position:relative; }
  .letterhead { text-align:center; border-bottom: 2px solid #0a3d62; padding-bottom: 8px; }
  .letterhead .small-text { font-size: 12px; }
  .letterhead h2 { margin: 4px 0; color:#0a3d62; letter-spacing: 1px; }
  .control-no { position: absolute; top: 18mm; right: 20mm; font-size: 11px; color:#555; }
  .title { text-align:center; margin-top: 36px; letter-spacing: 4px; font-size: 22px; font-weight: bold; color:#0a3d62; }
  .subtitle { text-align:center; font-style: italic; color:#555; margin-bottom: 28px; }
  .body-text { font-size: 14px; line-height: 1.8; text-align: justify; padding: 0 12px; }
  .body-text strong.school { text-transform: uppercase; }
  .signatory { margin-top: 60px; text-align: center; }
  .signatory .sigline { display: inline-block; min-width: 280px; border-bottom: 1px solid #222; padding-bottom: 4px; }
  .signatory img.esig { max-height: 70px; display: block; margin: 0 auto -10px; }
  .signatory .name { font-weight: bold; text-transform: uppercase; }
  .footer { position: absolute; bottom: 18mm; left: 20mm; right: 20mm;
            display: flex; justify-content: space-between; align-items: flex-end;
            font-size: 11px; color:#555; }
  .qr { text-align: center; }
  .qr img { width: 110px; height: 110px; }
  .verify-text { font-size: 10px; }
  .actions { text-align:center; margin: 12px 0; }
  .actions button { padding: 8px 18px; font-size: 14px; cursor:pointer; }
  @media print { .actions { display:none; } body { background:#fff; } }
</style>
</head>
<body>

<div class="actions">
  <button onclick="window.print()">Print</button>
  <button onclick="window.close()">Close</button>
</div>

<div class="page">
  <div class="control-no">Control No.: <strong><?= htmlspecialchars($row['control_no']) ?></strong></div>

  <div class="letterhead">
    <div class="small-text">Republic of the Philippines</div>
    <div class="small-text">Department of Education</div>
    <h2>REGION XI - DAVAO REGION</h2>
    <div class="small-text">Curriculum and Learning Management Division (CLMD)</div>
  </div>

  <div class="title">CERTIFICATION</div>
  <div class="subtitle">TO WHOM IT MAY CONCERN:</div>

  <div class="body-text">
    <p>This is to certify that <strong class="school"><?= htmlspecialchars($row['school_name']) ?></strong>,
    a <?= strtolower($row['school_type']) ?> school
    <?php if (!empty($row['municipality']) || !empty($row['school_address'])): ?>
      located at <?= htmlspecialchars(trim(($row['school_address'] ?? '').' '.($row['municipality'] ?? ''), ', ')) ?>,
    <?php endif; ?>
    under the supervision of the <?= htmlspecialchars($row['division_name']) ?>,
    has submitted the document entitled
    <em>"<?= htmlspecialchars($row['document_title']) ?>"</em>
    (<?= htmlspecialchars($row['document_type']) ?>),
    which has been duly <strong>reviewed and approved</strong> by the
    Curriculum and Learning Management Division, DepEd Region XI on
    <strong><?= date('F d, Y', strtotime($row['approved_at'] ?: $row['reviewed_at'])) ?></strong>.</p>

    <p>This Certification is being issued upon the request of the school for whatever purpose
    it may serve.</p>

    <p>Issued this <?= date('jS') ?> day of <?= date('F Y') ?> at DepEd Region XI, Davao City, Philippines.</p>
  </div>

  <div class="signatory">
    <?php if (!empty($settings['signature_path']) && file_exists(FCPATH.$settings['signature_path'])): ?>
      <img class="esig" src="<?= base_url($settings['signature_path']) ?>" alt="e-signature">
    <?php endif; ?>
    <div class="sigline">
      <div class="name"><?= htmlspecialchars($settings['chief_name'] ?: 'CLMD Chief') ?></div>
      <div><?= htmlspecialchars($settings['chief_position'] ?: 'Chief Education Supervisor, CLMD') ?></div>
    </div>
  </div>

  <div class="footer">
    <div>
      <div><strong>Verify authenticity:</strong></div>
      <div class="verify-text"><?= htmlspecialchars($verify_url) ?></div>
      <div class="verify-text">Document approved on <?= date('F d, Y H:i', strtotime($row['approved_at'] ?: $row['reviewed_at'])) ?></div>
    </div>
    <div class="qr">
      <img src="<?= htmlspecialchars($qr_url) ?>" alt="QR Code">
      <div class="verify-text">Scan to verify</div>
    </div>
  </div>
</div>

<script>
  // Auto-print after a slight delay (optional)
  // window.addEventListener('load', () => setTimeout(()=>window.print(), 400));
</script>
</body>
</html>
