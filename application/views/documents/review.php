<div class="card">
  <div class="card-header bg-white"><strong>Review Document</strong></div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Title</dt><dd class="col-sm-9"><?= htmlspecialchars($row['document_title']) ?></dd>
      <dt class="col-sm-3">Type</dt><dd class="col-sm-9"><?= htmlspecialchars($row['document_type']) ?></dd>
      <dt class="col-sm-3">School</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['school_name']) ?>
        <span class="badge <?= $row['school_type']==='Private'?'bg-info':'bg-secondary' ?>"><?= $row['school_type'] ?></span></dd>
      <dt class="col-sm-3">Division</dt><dd class="col-sm-9"><?= htmlspecialchars($row['division_name'] ?? '—') ?></dd>
      <dt class="col-sm-3">Submitted by</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['submitted_by_name']) ?>
        on <?= date('M d, Y H:i', strtotime($row['created_at'])) ?></dd>
      <dt class="col-sm-3">File</dt>
      <dd class="col-sm-9">
        <?php if (!empty($row['file_path'])): ?>
          <a target="_blank" href="<?= base_url($row['file_path']) ?>"><i class="bi bi-file-earmark"></i> Open file</a>
        <?php else: ?>—<?php endif; ?>
      </dd>
      <?php if (!empty($row['remarks'])): ?>
        <dt class="col-sm-3">Submitter Remarks</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['remarks'])) ?></dd>
      <?php endif; ?>
    </dl>

    <hr>
    <form method="post">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="mb-3">
        <label class="form-label">Review Notes</label>
        <textarea name="review_notes" class="form-control" rows="3"
                  placeholder="Optional notes for the division..."></textarea>
      </div>
      <a href="<?= site_url('documents') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" name="action" value="approve" class="btn btn-success">
        <i class="bi bi-check2-circle"></i> Approve
      </button>
      <button type="submit" name="action" value="revise" class="btn btn-primary"
              onclick="return confirm('Return for compliance?');">
        <i class="bi bi-arrow-counterclockwise"></i> Return for Compliance
      </button>
      <button type="submit" name="action" value="reject" class="btn btn-danger"
              onclick="return confirm('Reject this document?');">
        <i class="bi bi-x-circle"></i> Reject
      </button>
    </form>
  </div>
</div>
