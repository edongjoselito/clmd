<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Division Endorsement' : 'Division Endorsement' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <?php if ($is_edit && !empty($row['review_notes']) && in_array($row['status'], ['Revised','Rejected'], true)): ?>
      <div class="alert alert-warning">
        <strong>Reviewer Notes:</strong> <?= nl2br(htmlspecialchars($row['review_notes'])) ?>
      </div>
    <?php endif; ?>

    <?php if (empty($schools)): ?>
      <div class="alert alert-info">
        You have no active schools yet. Please <a href="<?= site_url('schools/create') ?>">add a school</a> first.
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      
      <!-- School Selection -->
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">School *</label>
          <?php if ($is_edit): ?>
            <?php $school_name = '';
            foreach ($schools as $s) {
                if ((int)$s['school_id'] === (int)$row['school_id']) {
                    $school_name = $s['school_name'] . ' (' . $s['school_type'] . ')';
                    break;
                }
            } ?>
            <input type="text" class="form-control" value="<?= htmlspecialchars($school_name) ?>" disabled>
            <input type="hidden" name="school_id" value="<?= htmlspecialchars($row['school_id']) ?>">
          <?php else: ?>
            <select name="school_id" class="form-select select2" required style="width: 100%;">
              <option value="">— Select school —</option>
              <?php $sel = set_value('school_id', $row['school_id'] ?? ''); ?>
              <?php foreach ($schools as $s): ?>
                <option value="<?= $s['school_id'] ?>"
                  <?= (int)$sel === (int)$s['school_id'] ? 'selected':'' ?>>
                  <?= htmlspecialchars($s['school_name']) ?> (<?= $s['school_type'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
        </div>
      </div>

      <hr>

      <!-- Current and Strengthened Curriculum Side by Side -->
      <div class="row g-4">
        <!-- Current Curriculum -->
        <div class="col-md-6">
          <h5 class="mb-3">Current Curriculum</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Track *</label>
              <select name="current_track" class="form-select" required>
                <option value="">— Select Track —</option>
                <?php
                $current_tracks = array_filter(array_map('trim', explode("\n", $settings['current_tracks'] ?? 'TVL Track')));
                foreach ($current_tracks as $track): ?>
                  <option value="<?= htmlspecialchars($track) ?>" <?= set_value('current_track', $row['current_track'] ?? '') === $track ? 'selected' : '' ?>><?= htmlspecialchars($track) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Strand *</label>
              <?php $selected_current_strands = array_filter(array_map('trim', explode(',', set_value('current_strand', $row['current_strand'] ?? '')))); ?>
              <?php
              $current_strands_text = trim($settings['current_strands'] ?? '') ?: "I.A. Strand\nH.E. Strand\nICT Strand";
              $current_strands = array_filter(array_map('trim', explode("\n", $current_strands_text)));
              ?>
              <div class="border rounded p-2">
                <?php foreach ($current_strands as $index => $strand): ?>
                  <div class="form-check">
                    <input class="form-check-input current-strand-select" type="checkbox" name="current_strand_values[]" value="<?= htmlspecialchars($strand) ?>" id="current_strand_<?= $index ?>" <?= in_array($strand, $selected_current_strands, true) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="current_strand_<?= $index ?>"><?= htmlspecialchars($strand) ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="current_strand" id="current_strand_input" value="<?= htmlspecialchars(implode(', ', $selected_current_strands)) ?>">
              <small class="text-muted">Select all applicable strands.</small>
            </div>
            <div class="col-12">
              <label class="form-label">Specializations *</label>
              <div id="current_specializations_container" class="border p-3 rounded bg-light" style="min-height: 60px;">
                <small class="text-muted">Select a strand first to see available specializations.</small>
              </div>
              <input type="hidden" name="current_specializations" id="current_specializations_input" value="<?= htmlspecialchars(set_value('current_specializations', $row['current_specializations'] ?? '')) ?>">
            </div>
          </div>
        </div>

        <!-- Strengthened Curriculum -->
        <div class="col-md-6">
          <h5 class="mb-3">Strengthened Curriculum</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Track *</label>
              <select name="strengthened_track" class="form-select" required>
                <option value="">— Select Track —</option>
                <?php
                $strengthened_tracks = array_filter(array_map('trim', explode("\n", $settings['strengthened_tracks'] ?? 'TechPro Track')));
                foreach ($strengthened_tracks as $track): ?>
                  <option value="<?= htmlspecialchars($track) ?>" <?= set_value('strengthened_track', $row['strengthened_track'] ?? '') === $track ? 'selected' : '' ?>><?= htmlspecialchars($track) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Strand *</label>
              <?php $selected_strengthened_strands = array_filter(array_map('trim', explode(',', set_value('strengthened_strand', $row['strengthened_strand'] ?? '')))); ?>
              <?php
              $strengthened_strands_text = trim($settings['strengthened_strands'] ?? '') ?: "Industrial Technologies\nHospitality and Tourism\nICT Support and Computer Programming Technologies";
              $strengthened_strands = array_filter(array_map('trim', explode("\n", $strengthened_strands_text)));
              ?>
              <div class="border rounded p-2">
                <?php foreach ($strengthened_strands as $index => $strand): ?>
                  <div class="form-check">
                    <input class="form-check-input strengthened-strand-select" type="checkbox" name="strengthened_strand_values[]" value="<?= htmlspecialchars($strand) ?>" id="strengthened_strand_<?= $index ?>" <?= in_array($strand, $selected_strengthened_strands, true) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="strengthened_strand_<?= $index ?>"><?= htmlspecialchars($strand) ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="strengthened_strand" id="strengthened_strand_input" value="<?= htmlspecialchars(implode(', ', $selected_strengthened_strands)) ?>">
              <small class="text-muted">Select all applicable strands.</small>
            </div>
            <div class="col-12">
              <label class="form-label">Specializations *</label>
              <div id="strengthened_specializations_container" class="border p-3 rounded bg-light" style="min-height: 60px;">
                <small class="text-muted">Select a strand first to see available specializations.</small>
              </div>
              <input type="hidden" name="strengthened_specializations" id="strengthened_specializations_input" value="<?= htmlspecialchars(set_value('strengthened_specializations', $row['strengthened_specializations'] ?? '')) ?>">
            </div>
          </div>
        </div>
      </div>

      <hr>
      <div class="card border-info mb-4">
        <div class="card-header bg-info-subtle"><strong>Review Curriculum Selections</strong></div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-md-6">
              <h6>Current Curriculum</h6>
              <div><strong>Track:</strong> <span id="review_current_track">Not selected</span></div>
              <div class="mt-2"><strong>Strands:</strong><ul id="review_current_strands" class="mb-0"></ul></div>
              <div class="mt-2"><strong>Specializations:</strong><ul id="review_current_specializations" class="mb-0"></ul></div>
            </div>
            <div class="col-md-6">
              <h6>Strengthened Curriculum</h6>
              <div><strong>Track:</strong> <span id="review_strengthened_track">Not selected</span></div>
              <div class="mt-2"><strong>Strands:</strong><ul id="review_strengthened_strands" class="mb-0"></ul></div>
              <div class="mt-2"><strong>Specializations:</strong><ul id="review_strengthened_specializations" class="mb-0"></ul></div>
            </div>
          </div>
        </div>
      </div>

      <h5 class="mb-3">Attachments</h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Certification of Compliance (PDF only, max 50MB) <?= !$is_edit ? '*' : '' ?></label>
          <input type="file" name="cert_file" class="form-control" accept=".pdf,application/pdf" <?= !$is_edit ? 'required' : '' ?>>
          <?php if (!empty($pair['certification']['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($pair['certification']['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <label class="form-label">Endorsement (PDF only, max 50MB) <?= !$is_edit ? '*' : '' ?></label>
          <input type="file" name="endorse_file" class="form-control" accept=".pdf,application/pdf" <?= !$is_edit ? 'required' : '' ?>>
          <?php if (!empty($pair['endorsement']['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($pair['endorsement']['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
      </div>

      <hr>
      <a href="<?= site_url('documents') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-cloud-upload"></i> <?= $is_edit ? 'Update Document' : 'Submit Both Documents' ?>
      </button>
    </form>
  </div>
</div>

<script>
<?php
$current_specs_json = $settings['current_specializations'] ?? '{}';
$strengthened_specs_json = $settings['strengthened_specializations'] ?? '{}';
?>

const currentSpecializations = <?= $current_specs_json ?>;
const strengthenedSpecializations = <?= $strengthened_specs_json ?>;

function renderSpecializations(strands, containerId, inputId, specsMap) {
  const container = document.getElementById(containerId);
  const input = document.getElementById(inputId);
  const currentValue = input.value ? input.value.split(',').map(s => s.trim()) : [];
  const selectedStrands = Array.isArray(strands) ? strands : (strands ? [strands] : []);
  const specs = [...new Set(selectedStrands.reduce((allSpecs, strand) => {
    return allSpecs.concat(specsMap[strand] || []);
  }, []))];

  if (!specs.length) {
    container.innerHTML = '<small class="text-muted">Select a strand first to see available specializations.</small>';
    input.value = '';
    return;
  }

  let html = '<div class="row">';
  specs.forEach((spec, index) => {
    const checked = currentValue.includes(spec) ? 'checked' : '';
    html += `
      <div class="col-md-6 mb-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="${inputId}_check" value="${spec}" id="${inputId}_${index}" ${checked} onchange="updateSpecializations('${inputId}')">
          <label class="form-check-label" for="${inputId}_${index}">${spec}</label>
        </div>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
  updateSpecializations(inputId);
}

function updateSpecializations(inputId) {
  const checkboxes = document.querySelectorAll(`input[name="${inputId}_check"]:checked`);
  const values = Array.from(checkboxes).map(cb => cb.value);
  document.getElementById(inputId).value = values.join(', ');
  updateCurriculumReview();
}

function setReviewList(id, values) {
  const list = document.getElementById(id);
  list.innerHTML = '';

  if (!values.length) {
    const item = document.createElement('li');
    item.textContent = 'Not selected';
    list.appendChild(item);
    return;
  }

  values.forEach(value => {
    const item = document.createElement('li');
    item.textContent = value;
    list.appendChild(item);
  });
}

function updateCurriculumReview() {
  const currentTrack = document.querySelector('select[name="current_track"]');
  const strengthenedTrack = document.querySelector('select[name="strengthened_track"]');
  const currentStrands = document.querySelectorAll('input[name="current_strand_values[]"]');
  const strengthenedStrands = document.querySelectorAll('input[name="strengthened_strand_values[]"]');

  document.getElementById('review_current_track').textContent = currentTrack.value || 'Not selected';
  document.getElementById('review_strengthened_track').textContent = strengthenedTrack.value || 'Not selected';
  setReviewList('review_current_strands', Array.from(currentStrands).filter(input => input.checked).map(input => input.value));
  setReviewList('review_strengthened_strands', Array.from(strengthenedStrands).filter(input => input.checked).map(input => input.value));
  setReviewList('review_current_specializations', document.getElementById('current_specializations_input').value.split(',').map(value => value.trim()).filter(Boolean));
  setReviewList('review_strengthened_specializations', document.getElementById('strengthened_specializations_input').value.split(',').map(value => value.trim()).filter(Boolean));
}

document.addEventListener('DOMContentLoaded', function() {
  const currentStrands = document.querySelectorAll('input[name="current_strand_values[]"]');
  const strengthenedStrands = document.querySelectorAll('input[name="strengthened_strand_values[]"]');

  function selectedValues(inputs) {
    return Array.from(inputs).filter(input => input.checked).map(input => input.value);
  }

  function syncStrands(inputs, inputId) {
    document.getElementById(inputId).value = selectedValues(inputs).join(', ');
  }

  document.querySelector('select[name="current_track"]').addEventListener('change', updateCurriculumReview);
  document.querySelector('select[name="strengthened_track"]').addEventListener('change', updateCurriculumReview);

  if (currentStrands.length) {
    currentStrands.forEach(function(strand) {
      strand.addEventListener('change', function() {
        syncStrands(currentStrands, 'current_strand_input');
        renderSpecializations(selectedValues(currentStrands), 'current_specializations_container', 'current_specializations_input', currentSpecializations);
      });
    });
    // Trigger on load if editing
    if (selectedValues(currentStrands).length) {
      renderSpecializations(selectedValues(currentStrands), 'current_specializations_container', 'current_specializations_input', currentSpecializations);
    }
  }

  if (strengthenedStrands.length) {
    strengthenedStrands.forEach(function(strand) {
      strand.addEventListener('change', function() {
        syncStrands(strengthenedStrands, 'strengthened_strand_input');
        renderSpecializations(selectedValues(strengthenedStrands), 'strengthened_specializations_container', 'strengthened_specializations_input', strengthenedSpecializations);
      });
    });
    // Trigger on load if editing
    if (selectedValues(strengthenedStrands).length) {
      renderSpecializations(selectedValues(strengthenedStrands), 'strengthened_specializations_container', 'strengthened_specializations_input', strengthenedSpecializations);
    }
  }

  updateCurriculumReview();
});
</script>
