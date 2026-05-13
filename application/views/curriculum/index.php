<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Curriculum</h5>
  <?php if ($_user['role'] === 'regional'): ?>
    <a href="<?= site_url('curriculum/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> New Curriculum
    </a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Title</th><th>Grade</th><th>Subject</th><th>SY</th>
          <th>File</th><th>Status</th><th>Posted</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">No curriculum yet.</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['title']) ?>
              <div class="small text-muted"><?= htmlspecialchars($r['created_by_name'] ?? '') ?></div>
            </td>
            <td><?= htmlspecialchars($r['grade_level']) ?></td>
            <td><?= htmlspecialchars($r['subject']) ?></td>
            <td><?= htmlspecialchars($r['school_year'] ?? '—') ?></td>
            <td>
              <?php if (!empty($r['file_path'])): ?>
                <a target="_blank" href="<?= base_url($r['file_path']) ?>"><i class="bi bi-file-earmark-arrow-down"></i> View</a>
              <?php else: ?>—<?php endif; ?>
            </td>
            <td>
              <?php if ((int)$r['is_active'] === 1): ?>
                <span class="badge bg-success">Active</span>
              <?php else: ?>
                <span class="badge bg-secondary">Archived</span>
              <?php endif; ?>
            </td>
            <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
            <td class="text-end">
              <?php if ($_user['role'] === 'regional'): ?>
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('curriculum/edit/'.$r['curriculum_id']) ?>">
                  <i class="bi bi-pencil"></i>
                </a>
                <a class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this curriculum?');"
                   href="<?= site_url('curriculum/delete/'.$r['curriculum_id']) ?>">
                  <i class="bi bi-trash"></i>
                </a>
              <?php else: ?>—<?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
