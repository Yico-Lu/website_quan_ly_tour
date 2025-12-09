<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Chọn Năm</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>reports/revenue-table" class="row g-3">
            <div class="col-md-3">
                <label for="year" class="form-label">Năm</label>
                <select class="form-select" id="year" name="year" onchange="this.form.submit()">
                    <?php for ($y = date('Y') + 1; $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= ($year ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </form>
    </div>
</div>