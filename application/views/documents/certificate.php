<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certification - <?= htmlspecialchars($cert['control_no']) ?></title>
<style>
  @page { size: A4; margin: 3mm 8mm 0 8mm; }
  * { box-sizing: border-box; }
  body { font-family: 'Calibri', Arial, sans-serif; color:#222; margin:0; padding:0; background:#fff; }
  .page { width: 210mm; min-height: 297mm; padding: 16mm 8mm 12.7mm 8mm; margin: 0 auto; background:#fff; position:relative; }
  .letterhead { text-align:center; padding-bottom: 8px; }
  .letterhead .small-text { font-size: 12px; }
  .letterhead h2 { margin: 4px 0; color:#0a3d62; letter-spacing: 1px; }
  .verification-group { position: absolute; bottom: 35mm; right: 18mm; border: 1px solid #222; padding: 10px 14px; background: #fff; text-align: center; }
  .qr-container .control-no { font-size: 11px; color:#555; margin-bottom: 6px; }
  .title { text-align:center; margin-top: 32px; letter-spacing: 4px; font-size: 22px; font-weight: bold; color:#0a3d62; }
  .subtitle { text-align:center; font-style: italic; color:#555; margin-bottom: 22px; }
  .body-text { font-size: 13.5px; line-height: 1.75; text-align: justify; padding: 0 12px; }
  .body-text strong.school { text-transform: uppercase; }
  .doc-list { margin: 14px 24px; font-size: 13.5px; }
  .doc-list li { margin-bottom: 6px; }
  .curriculum-title { font-size: 14px; font-weight: bold; margin: 18px 0 6px 0; color: #0a3d62; }
  .curriculum-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 13px; line-height: 1; }
  .curriculum-table th { background: #0a3d62; color: #fff; padding: 7px 10px; text-align: left; border: 1px solid #0a3d62; }
  .curriculum-table td { border: 1px solid #999; padding: 8px 10px; vertical-align: top; }
  .curriculum-table td ul { margin: 0; padding-left: 18px; }
  .curriculum-table td li { margin-bottom: 3px; }
  .signatory { margin-top: 50px; text-align: center; }
  .signatory .sig-area { display: flex; justify-content: center; align-items: flex-end; gap: 20px; margin-bottom: -8px; }
  .signatory img.esig { max-height: 70px; }
  .signatory .sigline { display: inline-block; min-width: 300px; padding-bottom: 4px; }
  .signatory .name { font-weight: bold; text-transform: uppercase; }
  .footer { position: absolute; bottom: 0; left: 18mm; right: 18mm;
            display: flex; justify-content: space-between; align-items: flex-end;
            font-size: 11px; color:#555; }
  .footer-image { position: absolute; bottom: 0; left: 18mm; right: 18mm; text-align: center; }
  .footer-image img { width: 100%; height: auto; max-height: 22mm; object-fit: contain; }
  .qr-container { text-align: center; }
  .qr-container img { display: block; margin: 0 auto; }
  .qr-container img.qr-code { width: 55px; height: 55px; }
  .qr-container .initials-sig { max-height: 25px; width: auto; margin-bottom: 3px; }
  .verify-text { font-size: 8px; color: #555; margin-top: 3px; line-height: 1.2; }
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
  <div class="letterhead">
    <?php if (!empty($settings['letterhead_path'])): ?>
      <img src="<?= base_url($settings['letterhead_path']) ?>" alt="Letterhead" style="width:100%; height:auto;">
    <?php else: ?>
      <div class="small-text">Republic of the Philippines</div>
      <div class="small-text">Department of Education</div>
      <h2>REGION XI - DAVAO REGION</h2>
      <div class="small-text">Curriculum and Learning Management Division (CLMD)</div>
    <?php endif; ?>
  </div>

  <div class="title">CERTIFICATION</div>

  <?php
    $addr_parts = array_filter([$cert['barangay'] ?? '', $cert['city'] ?? '', $cert['province'] ?? '']);
    $addr = $addr_parts ? implode(', ', $addr_parts) : '';
  ?>

  <?php
    $parse_specs = function ($text) {
        if (empty($text)) return [];
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $text))));
    };
    $current_specs    = $parse_specs($cert['current_specializations']);
    $strengthened_specs = $parse_specs($cert['strengthened_specializations']);
    $current_strands = $parse_specs($cert['current_strand']);
    $strengthened_strands = $parse_specs($cert['strengthened_strand']);
    if (empty($current_strands)) $current_strands = ['—'];
    if (empty($strengthened_strands)) $strengthened_strands = ['—'];
    $map_specs_to_strands = function ($selected_specs, $strands, $settings_json) {
        $configured_specs = json_decode($settings_json ?: '{}', true);
        $mapped_specs = [];
        foreach ($strands as $strand) {
            $available_specs = is_array($configured_specs[$strand] ?? null) ? $configured_specs[$strand] : [];
            $mapped_specs[$strand] = array_values(array_intersect($selected_specs, $available_specs));
        }
        return $mapped_specs;
    };
    $current_specs_by_strand = $map_specs_to_strands($current_specs, $current_strands, $settings['current_specializations'] ?? '{}');
    $strengthened_specs_by_strand = $map_specs_to_strands($strengthened_specs, $strengthened_strands, $settings['strengthened_specializations'] ?? '{}');
  ?>

  <div class="body-text">
    <p>This is to certify that <strong class="school"><?= htmlspecialchars($cert['school_name']) ?> - <?= htmlspecialchars($addr) ?>, <?= htmlspecialchars($cert['division_name']) ?></strong> is compliant to DepEd Order No. 54, S. 2022 known as the Guidelines on the Selection of Senior High School Technical-Vocational-Livelihood (SHS-TVL) Specializations as per recommendation of the Curriculum Implementation Division (CID) Chief Education Supervisor and the Schools Division Superintendent for School Year 2026-2027.</p>

    <div class="curriculum-title">Current Curriculum</div>
    <table class="curriculum-table">
      <thead>
        <tr>
          <th style="width: 30%;">Track</th>
          <th style="width: 30%;">Strand</th>
          <th style="width: 40%;">Specializations</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($current_strands as $index => $strand): ?>
        <tr>
          <?php if ($index === 0): ?>
            <td rowspan="<?= count($current_strands) ?>"><?= htmlspecialchars($cert['current_track']) ?></td>
          <?php endif; ?>
          <td><?= htmlspecialchars($strand) ?></td>
          <td>
            <?php if (!empty($current_specs_by_strand[$strand])): ?>
              <ul>
                <?php foreach ($current_specs_by_strand[$strand] as $spec): ?>
                  <li><?= htmlspecialchars($spec) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="curriculum-title">Clustered Electives</div>
    <table class="curriculum-table">
      <thead>
        <tr>
          <th style="width: 30%;">Track</th>
          <th style="width: 30%;">Strand</th>
          <th style="width: 40%;">Specializations</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($strengthened_strands as $index => $strand): ?>
        <tr>
          <?php if ($index === 0): ?>
            <td rowspan="<?= count($strengthened_strands) ?>"><?= htmlspecialchars($cert['strengthened_track']) ?></td>
          <?php endif; ?>
          <td><?= htmlspecialchars($strand) ?></td>
          <td>
            <?php if (!empty($strengthened_specs_by_strand[$strand])): ?>
              <ul>
                <?php foreach ($strengthened_specs_by_strand[$strand] as $spec): ?>
                  <li><?= htmlspecialchars($spec) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="signatory">
    <div class="sig-area">
      <?php if (!empty($settings['signature_path'])): ?>
        <img class="esig" src="<?= base_url($settings['signature_path']) ?>" alt="e-signature">
      <?php endif; ?>
    </div>
    <div class="sigline">
      <div class="name"><?= htmlspecialchars($settings['chief_name'] ?: 'CLMD Chief') ?></div>
      <div><?= htmlspecialchars($settings['chief_position'] ?: 'Chief Education Supervisor, CLMD') ?></div>
    </div>
  </div>

  <div class="verification-group">
    <div class="qr-container">
      <div class="control-no">
        Control No.:<br><strong><?= htmlspecialchars($cert['control_no']) ?></strong>
      </div>
      <?php if (!empty($settings['initials_signature_path'])): ?>
        <img class="initials-sig" src="<?= base_url($settings['initials_signature_path']) ?>" alt="initials">
      <?php endif; ?>
      <img class="qr-code" src="<?= htmlspecialchars($qr_url) ?>" alt="QR Code">
      <div class="verify-text">Scan to verify authenticity</div>
    </div>
  </div>

  <?php if (!empty($settings['footer_path'])): ?>
  <div class="footer-image">
    <img src="<?= base_url($settings['footer_path']) ?>" alt="Footer">
  </div>
  <?php else: ?>
  <div class="footer">
    <div>
      <div><strong>Verify authenticity:</strong></div>
      <div class="verify-text"><?= htmlspecialchars($verify_url) ?></div>
      <div class="verify-text">
        Approved: Certification <?= date('M d, Y', strtotime($cert['approved_at'] ?: $cert['reviewed_at'])) ?>
        &middot; Endorsement <?= date('M d, Y', strtotime($endorse['approved_at'] ?: $endorse['reviewed_at'])) ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

</body>
</html>
