<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit School' : 'New School' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">School Name *</label>
          <input type="text" name="school_name" class="form-control" required
                 value="<?= htmlspecialchars(set_value('school_name', $row['school_name'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">School ID / Code</label>
          <input type="text" name="school_code" class="form-control"
                 value="<?= htmlspecialchars(set_value('school_code', $row['school_code'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">School Type *</label>
          <select name="school_type" class="form-select" required>
            <?php $st = set_value('school_type', $row['school_type'] ?? 'Private'); ?>
            <option value="Private" <?= $st === 'Private' ? 'selected':'' ?>>Private</option>
            <option value="Public"  <?= $st === 'Public'  ? 'selected':'' ?>>Public</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" required
                 value="<?= htmlspecialchars(set_value('email', $row['email'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Province *</label>
          <select name="province" id="province" class="form-select" required>
            <option value="">Select Province</option>
            <?php foreach ($provinces as $p): ?>
              <option value="<?= htmlspecialchars($p['province']) ?>" <?= set_value('province', $row['province'] ?? '') === $p['province'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['province']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">City / Municipality *</label>
          <select name="city" id="city" class="form-select" required <?= empty($municipalities) ? 'disabled' : '' ?>>
            <option value="">Select Province First</option>
            <?php foreach ($municipalities as $m): ?>
              <option value="<?= htmlspecialchars($m['municipality']) ?>" <?= set_value('city', $row['city'] ?? '') === $m['municipality'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($m['municipality']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Barangay *</label>
          <select name="barangay" id="barangay" class="form-select" required <?= empty($barangays) ? 'disabled' : '' ?>>
            <option value="">Select City First</option>
            <?php foreach ($barangays as $b): ?>
              <option value="<?= htmlspecialchars($b['barangay']) ?>" <?= set_value('barangay', $row['barangay'] ?? '') === $b['barangay'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($b['barangay']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
              <?= !$is_edit || !empty($row['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('schools') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>

<script>
const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');
const barangaySelect = document.getElementById('barangay');

const savedCity = "<?= htmlspecialchars($row['city'] ?? '') ?>";
const savedBarangay = "<?= htmlspecialchars($row['barangay'] ?? '') ?>";

function populateSelect(select, options, defaultText, valueKey, selectedValue) {
  select.innerHTML = '<option value="">' + defaultText + '</option>';
  options.forEach(item => {
    const value = item[valueKey];
    const option = document.createElement('option');
    option.value = value;
    option.textContent = value;
    if (selectedValue && value === selectedValue) {
      option.selected = true;
    }
    select.appendChild(option);
  });
}

// Province change handler
provinceSelect.addEventListener('change', function() {
  const province = this.value;

  barangaySelect.innerHTML = '<option value="">Select City First</option>';
  barangaySelect.disabled = true;

  if (province) {
    const url = '<?= site_url('schools/ajax_municipalities') ?>?province=' + encodeURIComponent(province);
    fetch(url)
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw new Error(err.error || 'HTTP ' + response.status); });
      }
      return response.json();
    })
    .then(data => {
      if (!Array.isArray(data)) {
        console.error('Expected array from ajax_municipalities', data);
        return;
      }
      if (data.length === 0) {
        citySelect.innerHTML = '<option value="">No municipalities found</option>';
        citySelect.disabled = true;
        return;
      }
      populateSelect(citySelect, data, 'Select City/Municipality', 'municipality', savedCity);
      citySelect.disabled = false;
      if (savedCity && data.some(m => m.municipality === savedCity)) {
        citySelect.value = savedCity;
        citySelect.dispatchEvent(new Event('change'));
      }
    })
    .catch(error => {
      console.error('Failed to load municipalities:', error.message);
      citySelect.innerHTML = '<option value="">Error loading municipalities</option>';
      citySelect.disabled = true;
    });
  } else {
    citySelect.innerHTML = '<option value="">Select Province First</option>';
    citySelect.disabled = true;
  }
});

// City change handler
citySelect.addEventListener('change', function() {
  const province = provinceSelect.value;
  const municipality = this.value;

  if (province && municipality) {
    const url = '<?= site_url('schools/ajax_barangays') ?>?province=' + encodeURIComponent(province) + '&municipality=' + encodeURIComponent(municipality);
    fetch(url)
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw new Error(err.error || 'HTTP ' + response.status); });
      }
      return response.json();
    })
    .then(data => {
      if (!Array.isArray(data)) {
        console.error('Expected array from ajax_barangays', data);
        return;
      }
      if (data.length === 0) {
        barangaySelect.innerHTML = '<option value="">No barangays found</option>';
        barangaySelect.disabled = true;
        return;
      }
      populateSelect(barangaySelect, data, 'Select Barangay', 'barangay', savedBarangay);
      barangaySelect.disabled = false;

      // If saved barangay is not in the list, add it so the value is preserved
      if (savedBarangay && !data.some(b => b.barangay === savedBarangay)) {
        const option = document.createElement('option');
        option.value = savedBarangay;
        option.textContent = savedBarangay;
        option.selected = true;
        barangaySelect.appendChild(option);
      }
    })
    .catch(error => {
      console.error('Failed to load barangays:', error.message);
      barangaySelect.innerHTML = '<option value="">Error loading barangays</option>';
      barangaySelect.disabled = true;
    });
  } else {
    barangaySelect.innerHTML = '<option value="">Select City First</option>';
    barangaySelect.disabled = true;
  }
});
</script>
