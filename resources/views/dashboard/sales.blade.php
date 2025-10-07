{{-- dashboard/sales.blade.php - self-contained AJAX partial --}}
<div class="row mt-4" id="dashboard-sales-block">
  <div class="col-md-6">
    <div class="card card-round">
      <div class="card-header">
        <div class="card-head-row">
          <div class="card-title">Top Sellers (by quantity)</div>
          <div class="card-tools">
            <small id="salesMetaTop" class="text-muted">—</small>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="topLoading" class="text-center text-muted py-4">
          Loading top sellers...
        </div>
        <div class="table-responsive" id="topTableWrap" style="display:none;">
          <table class="table table-striped table-hover" id="topSellersTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Revenue</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="topEmpty" class="text-center text-muted py-4" style="display:none;">
          No sales data available for the selected timeframe.
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-round">
      <div class="card-header">
        <div class="card-head-row">
          <div class="card-title">Low Sellers (by quantity)</div>
          <div class="card-tools">
            <small id="salesMetaBottom" class="text-muted">—</small>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="bottomLoading" class="text-center text-muted py-4">
          Loading bottom sellers...
        </div>
        <div class="table-responsive" id="bottomTableWrap" style="display:none;">
          <table class="table table-striped table-hover" id="bottomSellersTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Revenue</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div id="bottomEmpty" class="text-center text-muted py-4" style="display:none;">
          No products found in the timeframe.
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const apiUrl = "{{ route('dashboard.sales') }}"; // route must return JSON (DashboardSalesController already does)
  const TOP_BODY = document.querySelector('#topSellersTable tbody');
  const BOTTOM_BODY = document.querySelector('#bottomSellersTable tbody');
  const topLoading = document.getElementById('topLoading');
  const bottomLoading = document.getElementById('bottomLoading');
  const topWrap = document.getElementById('topTableWrap');
  const bottomWrap = document.getElementById('bottomTableWrap');
  const topEmpty = document.getElementById('topEmpty');
  const bottomEmpty = document.getElementById('bottomEmpty');
  const metaTopEl = document.getElementById('salesMetaTop');
  const metaBottomEl = document.getElementById('salesMetaBottom');

  function fmtMoney(n) {
    return '₱ ' + Number(n || 0).toLocaleString();
  }

  function renderRows(tbody, rows) {
    if (!Array.isArray(rows) || rows.length === 0) return '';
    return rows.map((r, idx) => {
      const qty = Number(r.total_qty ?? 0);
      const rev = Number(r.total_revenue ?? 0);
      const name = r.name ?? r.product_name ?? r.product ?? '—';
      return `<tr>
        <td>${idx + 1}</td>
        <td>${escapeHtml(name)}</td>
        <td class="text-end">${qty.toLocaleString()}</td>
        <td class="text-end">${fmtMoney(rev.toFixed(2))}</td>
      </tr>`;
    }).join('');
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  async function loadSales() {
    // show loading
    topLoading.style.display = '';
    bottomLoading.style.display = '';
    topWrap.style.display = 'none';
    bottomWrap.style.display = 'none';
    topEmpty.style.display = 'none';
    bottomEmpty.style.display = 'none';
    metaTopEl.textContent = '—';
    metaBottomEl.textContent = '—';

    try {
      const res = await fetch(apiUrl, { headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();

      // expected JSON shape: { top: [...], bottom: [...], meta: { days, limit, metric } }
      const top = json.top ?? [];
      const bottom = json.bottom ?? [];
      const meta = json.meta ?? (json?.summary ?? null) ?? null;

      // update meta text
      const days = meta?.days ?? meta?.range_days ?? 'N/A';
      const limit = meta?.limit ?? 'N/A';
      const metric = meta?.metric ?? 'qty';
      metaTopEl.textContent = `${limit} items · last ${days} days · by ${metric}`;
      metaBottomEl.textContent = `${limit} items · last ${days} days · by ${metric}`;

      // render top
      if (top.length) {
        TOP_BODY.innerHTML = renderRows(TOP_BODY, top);
        topWrap.style.display = '';
      } else {
        TOP_BODY.innerHTML = '';
        topEmpty.style.display = '';
      }

      // render bottom
      if (bottom.length) {
        BOTTOM_BODY.innerHTML = renderRows(BOTTOM_BODY, bottom);
        bottomWrap.style.display = '';
      } else {
        BOTTOM_BODY.innerHTML = '';
        bottomEmpty.style.display = '';
      }

    } catch (err) {
      console.error('Failed to fetch dashboard sales:', err);
      TOP_BODY.innerHTML = '';
      BOTTOM_BODY.innerHTML = '';
      topEmpty.style.display = '';
      bottomEmpty.style.display = '';
    } finally {
      topLoading.style.display = 'none';
      bottomLoading.style.display = 'none';
    }
  }

  // initial load
  document.addEventListener('DOMContentLoaded', loadSales);
  // optional: refresh every X minutes:
  // setInterval(loadSales, 1000 * 60 * 5);

})();
</script>
@endpush
