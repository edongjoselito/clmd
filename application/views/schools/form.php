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
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">City / Municipality *</label>
          <select name="city" id="city" class="form-select" required disabled>
            <option value="">Select Province First</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Barangay *</label>
          <select name="barangay" id="barangay" class="form-select" required disabled>
            <option value="">Select City First</option>
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
const regionXI = {
  "Davao de Oro": {
    "Compostela": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Laak": ["Aguinaldo", "Baleguan", "Bansalan", "Bucaral", "Cabidian", "Cadiz", "Camansi", "Canticol", "Capalongan", "Carmen", "Casino", "Catubigan", "Concepcion", "Doña Carmen", "F. Mabilog", "Gabaldon", "Inacaban", "Kalabugao", "Kibuwa", "Kidawa", "Kiabo", "Langasian", "Liong", "Mahayahay", "Matawe", "Meliton M. Soriano", "Mipangi", "Nabunturan", "New Bohol", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Mabini": ["Anislag", "Bawani", "Cabacungan", "Cabuyao", "Calube", "Candudulong", "Cataningan", "Dizon", "Don Panfilo Mendoza", "Golden Valley", "Gubatan", "Kalubihan", "Katipunan", "La Fortuna", "Mabini", "Magangit", "Mambajao", "Mandaug", "Mapawa", "Marcelo", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Maco": ["Anibongon", "Bawani", "Binuangan", "Bucana", "Cabacungan", "Cabuyao", "Calube", "Candudulong", "Cataningan", "Dizon", "Don Panfilo Mendoza", "Golden Valley", "Gubatan", "Kalubihan", "Katipunan", "La Fortuna", "Mabini", "Magangit", "Mambajao", "Mandaug", "Mapawa", "Marcelo", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Mawab": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Monkayo": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Montevista": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Nabunturan": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Pantukan": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Poblacion": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"]
  },
  "Davao del Norte": {
    "Asuncion": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Carmen": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Kapalong": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "New Corella": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Panabo": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Samal": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Santo Tomas": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Tagum": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Talaingod": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Ventura": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"]
  },
  "Davao del Sur": {
    "Bansalan": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Davao City": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Digos": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Hagonoy": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Kiblawan": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Makar": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Malalag": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Matanao": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Matalam": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Padada": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Santa Cruz": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Sulop": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"]
  },
  "Davao Occidental": {
    "Don Marcelino": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Jose Abad Santos": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Malita": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Sarangani": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"]
  },
  "Davao Oriental": {
    "Baganga": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Boston": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Cateel": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Caraga": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Governor Generoso": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Lupon": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Manay": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Mati": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "San Isidro": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"],
    "Tarragona": ["Andap", "Biao", "Bobonawan", "Compostela", "Mabuhay", "Macaang", "Magangit", "Mapawa", "Mariano", "New Bataan", "New Corella", "New Leyte", "New Visayas", "Panansalan", "Poblacion", "San Jose", "San Miguel", "San Vicente", "Santa Cruz", "Siocon"]
  }
};

const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');
const barangaySelect = document.getElementById('barangay');

<?php if (!empty($row['province'])): ?>
const savedProvince = "<?= htmlspecialchars($row['province']) ?>";
const savedCity = "<?= htmlspecialchars($row['city']) ?>";
const savedBarangay = "<?= htmlspecialchars($row['barangay']) ?>";
<?php endif; ?>

// Populate provinces
Object.keys(regionXI).forEach(province => {
  const option = document.createElement('option');
  option.value = province;
  option.textContent = province;
  provinceSelect.appendChild(option);
});

<?php if (!empty($row['province'])): ?>
provinceSelect.value = savedProvince;
<?php endif; ?>

// Province change handler
provinceSelect.addEventListener('change', function() {
  const selectedProvince = this.value;
  citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
  barangaySelect.innerHTML = '<option value="">Select City First</option>';
  barangaySelect.disabled = true;

  if (selectedProvince && regionXI[selectedProvince]) {
    citySelect.disabled = false;
    Object.keys(regionXI[selectedProvince]).forEach(city => {
      const option = document.createElement('option');
      option.value = city;
      option.textContent = city;
      citySelect.appendChild(option);
    });

    <?php if (!empty($row['city'])): ?>
    if (regionXI[selectedProvince][savedCity]) {
      citySelect.value = savedCity;
      citySelect.dispatchEvent(new Event('change'));
    }
    <?php endif; ?>
  } else {
    citySelect.disabled = true;
  }
});

// City change handler
citySelect.addEventListener('change', function() {
  const selectedProvince = provinceSelect.value;
  const selectedCity = this.value;
  barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

  if (selectedProvince && selectedCity && regionXI[selectedProvince] && regionXI[selectedProvince][selectedCity]) {
    barangaySelect.disabled = false;
    regionXI[selectedProvince][selectedCity].forEach(barangay => {
      const option = document.createElement('option');
      option.value = barangay;
      option.textContent = barangay;
      barangaySelect.appendChild(option);
    });

    <?php if (!empty($row['barangay'])): ?>
    if (regionXI[selectedProvince][selectedCity].includes(savedBarangay)) {
      barangaySelect.value = savedBarangay;
    }
    <?php endif; ?>
  } else {
    barangaySelect.disabled = true;
  }
});

// Trigger province change on load if value exists
<?php if (!empty($row['province'])): ?>
provinceSelect.dispatchEvent(new Event('change'));
<?php endif; ?>
</script>
