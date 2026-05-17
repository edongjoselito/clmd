<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Schools</h5>
  <?php if ($_user['role'] === 'division'): ?>
    <a href="<?= site_url('schools/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> New School
    </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="schoolsTable" class="table table-hover align-middle" style="width:100%">
        <thead>
          <tr>
            <th>School Name</th>
            <th>Code</th>
            <th>Type</th>
            <th>Email</th>
            <th>Address</th>
            <?php if ($_user['role'] === 'regional'): ?><th>Division</th><?php endif; ?>
            <th>Status</th>
            <?php if ($_user['role'] === 'division'): ?><th class="text-end">Actions</th><?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['school_name']) ?></td>
              <td><?= htmlspecialchars($r['school_code'] ?? '—') ?></td>
              <td>
                <span class="badge <?= $r['school_type'] === 'Private' ? 'bg-info' : 'bg-secondary' ?>">
                  <?= htmlspecialchars($r['school_type']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($r['email'] ?? '—') ?: '—' ?></td>
              <td>
                <?php
                  $parts = array_filter([$r['barangay'] ?? '', $r['city'] ?? '', $r['province'] ?? '']);
                  echo $parts ? htmlspecialchars(implode(', ', $parts)) : '—';
                ?>
              </td>
              <?php if ($_user['role'] === 'regional'): ?>
                <td><?= htmlspecialchars($r['division_code'] ?? '—') ?></td>
              <?php endif; ?>
              <td>
                <?php if ((int)$r['is_active'] === 1): ?>
                  <span class="badge bg-success">Active</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactive</span>
                <?php endif; ?>
              </td>
              <?php if ($_user['role'] === 'division'): ?>
                <td class="text-end text-nowrap">
                  <a class="btn btn-sm btn-outline-primary" href="<?= site_url('schools/edit/'.$r['school_id']) ?>">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete this school?');"
                     href="<?= site_url('schools/delete/'.$r['school_id']) ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#schoolsTable').DataTable({
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    order: [[0, 'asc']],
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search schools..."
    },
    columnDefs: [
      { orderable: false, targets: -1 }
    ]
  });
});
</script>
