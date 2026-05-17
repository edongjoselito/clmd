<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certification - <?= htmlspecialchars($cert['control_no']) ?></title>
<style>
  @page { size: A4; margin: 18mm 18mm 20mm 18mm; }
  * { box-sizing: border-box; }
  body { font-family: 'Times New Roman', Times, serif; color:#222; margin:0; padding:0; background:#fff; }
  .page { width: 210mm; min-height: 297mm; padding: 16mm 18mm; margin: 0 auto; background:#fff; position:relative; }
  .letterhead { text-align:center; border-bottom: 2px solid #0a3d62; padding-bottom: 8px; }
  .letterhead .small-text { font-size: 12px; }
  .letterhead h2 { margin: 4px 0; color:#0a3d62; letter-spacing: 1px; }
  .control-no { position: absolute; top: 16mm; right: 18mm; font-size: 11px; color:#555; text-align:right; }
  .title { text-align:center; margin-top: 32px; letter-spacing: 4px; font-size: 22px; font-weight: bold; color:#0a3d62; }
  .subtitle { text-align:center; font-style: italic; color:#555; margin-bottom: 22px; }
  .body-text { font-size: 13.5px; line-height: 1.75; text-align: justify; padding: 0 12px; }
  .body-text strong.school { text-transform: uppercase; }
  .doc-list { margin: 14px 24px; font-size: 13.5px; }
  .doc-list li { margin-bottom: 6px; }
  .signatory { margin-top: 50px; text-align: center; }
  .signatory .sigline { display: inline-block; min-width: 300px; border-bottom: 1px solid #222; padding-bottom: 4px; }
  .signatory img.esig { max-height: 70px; display: block; margin: 0 auto -8px; }
  .signatory .name { font-weight: bold; text-transform: uppercase; }
  .footer { position: absolute; bottom: 14mm; left: 18mm; right: 18mm;
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
  <div class="control-no">
    Control No.:<br><strong><?= htmlspecialchars($cert['control_no']) ?></strong>
  </div>

  <div class="letterhead">
    <div class="small-text">Republic of the Philippines</div>
    <div class="small-text">Department of Education</div>
    <h2>REGION XI - DAVAO REGION</h2>
    <div class="small-text">Curriculum and Learning Management Division (CLMD)</div>
  </div>

  <div class="title">CERTIFICATION</div>
  <div class="subtitle">TO WHOM IT MAY CONCERN:</div>

  <div class="body-text">
    <p>This is to certify that <strong class="school"><?= htmlspecialchars($cert['school_name']) ?></strong>,
    a <?= strtolower($cert['school_type']) ?> school
    <?php
      $addr_parts = array_filter([$cert['barangay'] ?? '', $cert['city'] ?? '', $cert['province'] ?? '']);
      $addr = $addr_parts ? implode(', ', $addr_parts) : '';
    ?>
    <?php if ($addr !== ''): ?>
      located at <?= htmlspecialchars($addr) ?>,
    <?php endif; ?>
    under the supervision of the <?= htmlspecialchars($cert['division_name']) ?>,
    has been found <strong>compliant</strong> with
    <strong>DepEd Order No. 54, s. 2022</strong>, based on the documents
    submitted, reviewed, and duly approved by the Curriculum and Learning
    Management Division, DepEd Region XI.</p>

    <p>The following documents form part of this certification:</p>
    <ol class="doc-list">
      <li>
        <strong>Certification of Compliance to DepEd Order No. 54, s. 2022</strong>
        &mdash; <em><?= htmlspecialchars($cert['document_title']) ?></em>
        (Control No. <?= htmlspecialchars($cert['control_no']) ?>),
        approved on <?= date('F d, Y', strtotime($cert['approved_at'] ?: $cert['reviewed_at'])) ?>.
      </li>
      <li>
        <strong>Endorsement</strong>
        &mdash; <em><?= htmlspecialchars($endorse['document_title']) ?></em>
        (Control No. <?= htmlspecialchars($endorse['control_no']) ?>),
        approved on <?= date('F d, Y', strtotime($endorse['approved_at'] ?: $endorse['reviewed_at'])) ?>.
      </li>
    </ol>

    <p>This Certification is being issued upon the request of the school for
    whatever legal purpose it may serve.</p>

    <p>Issued this <?= date('jS') ?> day of <?= date('F Y') ?> at DepEd Region XI,
    Davao City, Philippines.</p>
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
      <div class="verify-text">
        Approved: Certification <?= date('M d, Y', strtotime($cert['approved_at'] ?: $cert['reviewed_at'])) ?>
        &middot; Endorsement <?= date('M d, Y', strtotime($endorse['approved_at'] ?: $endorse['reviewed_at'])) ?>
      </div>
    </div>
    <div class="qr">
      <img src="<?= htmlspecialchars($qr_url) ?>" alt="QR Code">
      <div class="verify-text">Scan to verify</div>
    </div>
  </div>
</div>

</body>
</html>
