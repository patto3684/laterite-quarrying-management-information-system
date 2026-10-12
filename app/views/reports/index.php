<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Reports & Analytics</h2>
        <p class="text-muted small">Comprehensive business intelligence dashboard for LQSMIS</p>
    </div>
    <div>
        <button class="print-page btn btn-primary btn-sm">Print Report</button>
    </div>
</div>

<!-- Filter Bar -->
<div class="card shadow-sm mb-4" id="reportFilterCard">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="dateRangeSelect" class="form-label fw-bold">Date Range</label>
                <select class="form-select" id="dateRangeSelect">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom Date</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Custom Range</label>
                <div class="d-flex gap-2">
                    <input type="date" class="form-control" id="customDateStart" style="display:none;">
                    <input type="date" class="form-control" id="customDateEnd" style="display:none;">
                </div>
                <small id="customRangeLabel" class="text-muted small">Select custom date to activate</small>
            </div>
            <div class="col-md-3">
                <label for="quarryLocationSelect" class="form-label fw-bold">Quarry Location</label>
                <select class="form-select" id="quarryLocationSelect">
                    <option value="all" selected>All Gasabo Sites</option>
                    <option value="jabana">Jabana Site</option>
                    <option value="ndera">Ndera Site</option>
                    <option value="gatsata">Gatsata Site</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-primary w-100" id="filterBtn" type="button">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .print-page, .print-toolbar, #reportFilterCard, .btn-sm, .btn-outline-primary, .btn-outline-success, .btn-outline-warning, .btn-outline-danger {
            display: none !important;
        }
        body, html {
            background: white !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        .row.g-4 {
            margin: 0 !important;
        }
    }
</style>

<!-- Report Cards Row 1 -->
<div class="row g-4 mb-4">
    <!-- Production Report Card -->
    <div class="col-md-6 col-lg-6">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #2563eb;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Production Report</h5>
                        <p class="text-muted small mb-0">Daily, weekly, monthly and yearly production summaries.</p>
                    </div>
                    <span class="badge bg-primary">Operational</span>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Total Produced</p>
                        <h3 id="productionTotal" class="fw-bold text-primary mb-0">0 <span class="fs-6 text-muted">tonnes</span></h3>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">This Period</p>
                        <h3 id="productionPeriod" class="fw-bold text-success mb-0">0 <span class="fs-6 text-muted">t</span></h3>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-primary w-100 mt-3" onclick="window.location.href='<?= APP_URL ?>/reports/production'">View Detailed Report →</button>
            </div>
        </div>
    </div>

    <!-- Finance Report Card -->
    <div class="col-md-6 col-lg-6">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #16a34a;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Finance Report</h5>
                        <p class="text-muted small mb-0">Revenue, expenses, payment status and outstanding balances.</p>
                    </div>
                    <span class="badge bg-success">Healthy</span>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Total Revenue</p>
                        <h3 id="financeRevenue" class="fw-bold text-success mb-0">0 <span class="fs-6 text-muted">RWF</span></h3>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Total Expenses</p>
                        <h3 id="financeExpenses" class="fw-bold text-danger mb-0">0 <span class="fs-6 text-muted">RWF</span></h3>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-success w-100 mt-3" onclick="window.location.href='<?= APP_URL ?>/reports/finance'">View Detailed Report →</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Cards Row 2 -->
<div class="row g-4">
    <!-- Logistics & Dispatch Report Card -->
    <div class="col-md-6 col-lg-6">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Logistics & Dispatch Report</h5>
                        <p class="text-muted small mb-0">Track haulage truck fleet metrics, total dispatched trips, and route distributions within Kigali.</p>
                    </div>
                    <span class="badge bg-warning text-dark">Active Fleet</span>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Dispatched Trips</p>
                        <h3 class="fw-bold text-warning mb-0">47 <span class="fs-6 text-muted">trips</span></h3>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Fleet Utilization</p>
                        <h3 class="fw-bold text-info mb-0">78% <span class="fs-6 text-muted">active</span></h3>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-warning w-100 mt-3" onclick="window.location.href='<?= APP_URL ?>/reports/logistics'">View Detailed Report →</button>
            </div>
        </div>
    </div>

    <!-- Safety & Environmental Report Card -->
    <div class="col-md-6 col-lg-6">
        <div class="card shadow-sm h-100" style="border-left: 4px solid #ef4444;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Safety & Environmental Report</h5>
                        <p class="text-muted small mb-0">Summarize site inspection passes, environmental rehabilitation fund allocations, and risk factor audits.</p>
                    </div>
                    <span class="badge bg-danger">1 Pending</span>
                </div>
                <hr class="my-3">
                <div class="row text-center">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Inspections Passed</p>
                        <h3 class="fw-bold text-success mb-0">12 <span class="fs-6 text-muted">sites</span></h3>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Rehab Fund Allocated</p>
                        <h3 class="fw-bold text-danger mb-0">2.4M <span class="fs-6 text-muted">RWF</span></h3>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger w-100 mt-3" onclick="window.location.href='<?= APP_URL ?>/reports/safety'">View Detailed Report →</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateRangeSelect = document.getElementById('dateRangeSelect');
        const customDateStart = document.getElementById('customDateStart');
        const customDateEnd = document.getElementById('customDateEnd');
        const customRangeLabel = document.getElementById('customRangeLabel');
        const quarryLocationSelect = document.getElementById('quarryLocationSelect');
        const filterBtn = document.getElementById('filterBtn');
        const productionTotal = document.getElementById('productionTotal');
        const productionPeriod = document.getElementById('productionPeriod');
        const financeRevenue = document.getElementById('financeRevenue');
        const financeExpenses = document.getElementById('financeExpenses');

        function formatNumber(value) {
            return new Intl.NumberFormat('en-US').format(value);
        }

        function formatCurrency(value) {
            if (value >= 1000000) {
                return formatNumber((value / 1000000).toFixed(1)) + 'M';
            }
            if (value >= 1000) {
                return formatNumber((value / 1000).toFixed(0)) + 'K';
            }
            return formatNumber(value);
        }

        function updateFilterVisibility() {
            if (dateRangeSelect.value === 'custom') {
                customDateStart.style.display = 'block';
                customDateEnd.style.display = 'block';
                customRangeLabel.style.display = 'none';
            } else {
                customDateStart.style.display = 'none';
                customDateEnd.style.display = 'none';
                customRangeLabel.style.display = 'block';
            }
        }

        async function fetchReportData() {
            const payload = {
                date_range: dateRangeSelect.value,
                quarry_location: quarryLocationSelect.value,
                custom_start: customDateStart.value || null,
                custom_end: customDateEnd.value || null,
            };

            const response = await fetch('<?= APP_URL ?>/reports/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                console.error('Report fetch failed:', response.statusText);
                return;
            }

            const data = await response.json();
            productionTotal.innerHTML = `${formatNumber(data.production.total)} <span class="fs-6 text-muted">tonnes</span>`;
            productionPeriod.innerHTML = `${formatNumber(data.production.period)} <span class="fs-6 text-muted">t</span>`;
            financeRevenue.innerHTML = `${formatCurrency(data.finance.revenue)} <span class="fs-6 text-muted">RWF</span>`;
            financeExpenses.innerHTML = `${formatCurrency(data.finance.expenses)} <span class="fs-6 text-muted">RWF</span>`;
        }

        async function applyFilters() {
            filterBtn.disabled = true;
            filterBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Loading...';
            await fetchReportData();
            filterBtn.disabled = false;
            filterBtn.innerHTML = '<i class="bi bi-funnel"></i> Filter';
        }

        dateRangeSelect.addEventListener('change', updateFilterVisibility);
        quarryLocationSelect.addEventListener('change', applyFilters);
        customDateStart.addEventListener('change', applyFilters);
        customDateEnd.addEventListener('change', applyFilters);

        filterBtn.addEventListener('click', applyFilters);

        // Initialize with current values
        updateFilterVisibility();
        fetchReportData();
    });
</script>
