<div class="main-header">

  <div class="main-header-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-text text-white fw-bold fs-4">
          <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="{{ config('app.name') }}" class="me-2" style="width: 25px; height: 25px; border-radius: 5px;">{{ config('app.name') }}
        </span>
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
  </div>

  <!-- Navbar Header -->
  <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid bg-neutral-300">
      <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
        <span class="fw-bold fs-5 text-dark"><h2>Librewhan <span class="text-warning">Cafe</span></h2>
      </nav>

      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

        {{-- Messages  --}}

        {{-- <li class="nav-item topbar-icon dropdown hidden-caret">
          <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-envelope"></i>
          </a>
          <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
            <li>
              <div class="dropdown-title d-flex justify-content-between align-items-center">
                Messages
                <a href="#" class="small">Mark all as read</a>
              </div>
            </li>
            <li>
              <div class="message-notif-scroll scrollbar-outer">
                <div class="notif-center">
                  <a href="#">
                    <div class="notif-img">
                      <img
                        src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                        alt="Img Profile"
                      />
                    </div>
                    <div class="notif-content">
                      <span class="subject">Static Message</span>
                      <span class="block"> Hello, World! </span>
                      <span class="time">X minutes ago</span>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <li>
              <a class="see-all" href="javascript:void(0);"
                >See all messages<i class="fa fa-angle-right"></i>
              </a>
            </li>
          </ul>
        </li> --}}

        {{-- Notification Bell --}}
        @php
          $usertype = strtolower(session('usertype', ''));
          $userrole = strtolower(session('user_role', ''));
          $isAdmin = ($usertype === 'admin' || $userrole === 'admin');
          $workspaceRole = session('workspace_role', 'sms');
        @endphp
        @if($isAdmin)

        <li class="nav-item topbar-icon dropdown hidden-caret">
          <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell" style="font-size: 20px;"></i>
            <span id="notifCount" class="notification" style="display:none;background:#dc3545;color:white;padding:2px 6px;border-radius:12px;font-size:0.75rem;margin-left:6px;">0</span>
          </a>

          <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown" id="notifDropdownMenu" style="width:320px;">
            <li>
              <div class="dropdown-title d-flex justify-content-between align-items-center">
                Notifications
                <a href="#" id="markAllAlertsRead" class="small">Mark all read</a>
              </div>
            </li>
            <li>
              <div class="notif-scroll scrollbar-outer" style="max-height:300px;overflow:auto;">
                <div class="notif-center" id="notifList">
                  <div class="text-center text-muted py-3">Loading...</div>
                </div>
              </div>
            </li>
            <li>
              <a class="see-all" href="{{ route('inventory.stock.alerts') }}">See all stock alerts<i class="fa fa-angle-right"></i></a>
            </li>
          </ul>
        </li>

        @endif

        <li class="nav-item topbar-user dropdown hidden-caret">
          <a
            class="dropdown-toggle profile-pic"
            data-bs-toggle="dropdown"
            href="#"
            aria-expanded="false"
          >
            <div class="avatar-sm">
              <img
                src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                alt="..."
                class="avatar-img rounded-circle"
              />
            </div>
            <span class="profile-username">
              <span class="op-7">Hi,</span>
              <span class="fw-bold">
                @if(session('user_role') === 'barista')
                  Barista
                @elseif(session('user_role') === 'admin')
                  admin
                @else
                  ROLE NOT SET
                @endif
              </span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    <img
                      src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                      alt="image profile"
                      class="avatar-img rounded"
                    />
                  </div>
                  <div class="u-text">
                    <h4>
                      @if(session('user_role') === 'barista')
                        Barista Account
                      @elseif(session('user_role') === 'admin')
                        Admin Account
                      @else
                        ROLE NOT SET
                      @endif
                    </h4>
                    <p class="text-muted">{{ session('user_email', 'admin@gmail.com') }}</p>
                    <div class="mb-2">
                      <span class="badge badge-{{ session('workspace_role') === 'sms' ? 'success' : 'primary' }}">
                        @if(session('workspace_role') === 'sms')
                          <i class="fas fa-cash-register me-1"></i>SMS Workspace
                        @else
                          <i class="fas fa-boxes me-1"></i>Inventory Workspace
                        @endif
                      </span>
                    </div>
                    <a
                      href="{{ route('profile.show') }}"
                      class="btn btn-xs btn-secondary btn-sm"
                      >View Profile</a
                    >
                  </div>
                </div>
              </li>
              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a>
                @if((auth()->check() && auth()->user()->usertype === 'admin'))
                <div class="dropdown-divider"></div>
                <div class="dropdown-header">Switch Workspace</div>
                @if(session('workspace_role') !== 'sms')
                <a class="dropdown-item" href="{{ route('dashboard') }}?workspace=sms">
                  <i class="fas fa-cash-register me-2"></i>Sales Management System
                </a>
                @endif
                @if(session('workspace_role') !== 'inventory')
                <a class="dropdown-item" href="{{ route('dashboard') }}?workspace=inventory">
                  <i class="fas fa-boxes me-2"></i>Inventory Management
                </a>
                @endif
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Account Setting</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="dropdown-item" style="border:none; background:none; padding:0;">
                      <i class="fas fa-sign-out-alt me-2"></i>Logout
                  </button>
              </form>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>

{{-- Place near the end of views/layouts/header.blade.php (inside @push('scripts') or directly before </body>) --}}
{{-- Replace previous notif CSS+JS with this block --}}
<style>
  /* Notification bell */
  #notifDropdown { position: relative; display: inline-block; }
  #notifDropdown .notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 20px;
    height: 20px;
    line-height: 18px;
    padding: 0 6px;
    background: #e3342f !important;
    color: #fff !important;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.18);
    z-index: 3000;
  }

  /* Options for Notification Bell animation */

  /* pulse + stronger highlight if alerts exist */
  /* #notifDropdown.has-alerts .fa-bell {
    color: #ff9100 !important;
    filter: drop-shadow(0 10px 18px rgba(255,124,0,.12)) !important;
    animation: bellPulse 2s infinite ease-in-out !important;
    transform-origin: center;
  } */
  @keyframes bellPulse {
    0% { transform: scale(1); }
    30% { transform: scale(1); }
    40% { transform: scale(2); }
    50% { transform: scale(1); }
    60% { transform: scale(2); }
    70% { transform: scale(1); }
    100% { transform: scale(1); }
  }

  /* Option 1: Swing  */
  @keyframes bellSwing {
    0%, 100% { transform: rotate(0deg); }
    5% { transform: rotate(15deg); }
    10% { transform: rotate(-10deg); transform: scale(1.5); }
    15% { transform: rotate(10deg); }
    20% { transform: rotate(-5deg); }
    30% { transform: rotate(0deg); transform: scale(1); }
  }

  /* Option 2: Shake */
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
  }

  /* Option 3: Bounce */
  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    25% { transform: translateY(-15px); }
    50% { transform: translateY(0); }
    75% { transform: translateY(-8px); }
  }

  /* Option 4: Pulse */
  @keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.8; }
  }

  /* Option 5: Ring (Rotate) */
  @keyframes ring {
    0% { transform: rotate(0deg); }
    5% { transform: rotate(30deg); }
    10% { transform: rotate(-28deg); }
    15% { transform: rotate(26deg); }
    20% { transform: rotate(-24deg); }
    25% { transform: rotate(22deg); }
    30% { transform: rotate(-20deg); }
    35% { transform: rotate(18deg); }
    40% { transform: rotate(-16deg); }
    45% { transform: rotate(14deg); }
    50% { transform: rotate(-12deg); }
    55% { transform: rotate(10deg); }
    60% { transform: rotate(-8deg); }
    65% { transform: rotate(6deg); }
    70% { transform: rotate(-4deg); }
    75% { transform: rotate(2deg); }
    80%, 100% { transform: rotate(0deg); }
  }

  /* Option 6: Glow Pulse */
  @keyframes glowPulse {
    0%, 100% {
      filter: drop-shadow(0 0 2px rgba(255,145,0,0.4));
    }
    50% {
      filter: drop-shadow(0 0 20px rgba(255,145,0,0.9));
    }
  }

  /* Option 7: Jello */
  @keyframes jello {
    0%, 100% { transform: skewX(0deg) skewY(0deg); }
    30% { transform: skewX(-12.5deg) skewY(-12.5deg); }
    40% { transform: skewX(6.25deg) skewY(6.25deg); }
    50% { transform: skewX(-3.125deg) skewY(-3.125deg); }
    65% { transform: skewX(1.5625deg) skewY(1.5625deg); }
    75% { transform: skewX(-0.78125deg) skewY(-0.78125deg); }
  }

  /* Option 8: Tada */
  @keyframes tada {
    0%, 100% { transform: scale(1) rotate(0deg); }
    10%, 20% { transform: scale(0.9) rotate(-3deg); }
    30%, 50%, 70%, 90% { transform: scale(1.1) rotate(3deg); }
    40%, 60%, 80% { transform: scale(1.1) rotate(-3deg); }
  }
  
  /* Option 9: Heartbeat */
  @keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    14% { transform: scale(1.2); }
    28% { transform: scale(1); }
    42% { transform: scale(1.2); }
    70% { transform: scale(1); }
  }

  /* Option 10: Wiggle */
  @keyframes wiggle {
    0%, 7% { transform: rotateZ(0deg); }
    15% { transform: rotateZ(-15deg); }
    20% { transform: rotateZ(10deg); }
    25% { transform: rotateZ(-10deg); }
    30% { transform: rotateZ(6deg); }
    35% { transform: rotateZ(-4deg); }
    40%, 100% { transform: rotateZ(0deg); }
  }

  /* Option 11: Flash */
  @keyframes flash {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0.3; }
  }

  /* Option 12: Rubber Band */
  @keyframes rubberBand {
    0%, 100% { transform: scale(1); }
    30% { transform: scaleX(1.25) scaleY(0.75); }
    40% { transform: scaleX(0.75) scaleY(1.25); }
    50% { transform: scaleX(1.15) scaleY(0.85); }
    65% { transform: scaleX(0.95) scaleY(1.05); }
    75% { transform: scaleX(1.05) scaleY(0.95); }
  }

  /* meme */
  @keyframes uia {
    0%, 100% { transform: scaleX(-2); }
    25% { transform: scaleX(2) scaleY(0.95); }
    50%, 100% { transform: scaleX(-2); }
    100% { transform: scaleX(2) scaleY(0.95); }
  }

  /* Call your chosen animation here */
  #notifDropdown.has-alerts .fa-bell {
    color: #ff9100 !important;
    animation: ring 2s infinite ease-in-out !important;
    transform-origin: top center;
  }

  /* Larger, bordered dropdown */
  .notif-box {
    min-width: 420px !important;
    max-width: 720px !important;
    width: auto !important;
    border: 1px solid rgba(0,0,0,0.10) !important;
    border-radius: 10px !important;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
    padding: 0 !important;
    background: #19162c !important;
    overflow: hidden;
    z-index: 3050 !important;
  }

  /* inner layout */
  .notif-box .dropdown-title { padding: 10px 14px; font-weight:700; border-bottom:1px solid rgba(0,0,0,0.04); }
  .notif-box .notif-scroll { max-height: 360px; overflow:auto; padding: 6px 8px; }
  .notif-box .dropdown-item {
    display:flex;
    gap:10px;
    align-items:flex-start;
    padding:10px;
    border-radius:8px;
    margin-bottom:6px;
    background:#ffffff;
    text-decoration:none;
    color:inherit;
  }
  .notif-box .dropdown-item:hover { background: #f8f9fb; }
  .notif-box .notif-message { font-size:0.86rem; color:#333; margin-top:4px; }
  .notif-box .notif-meta { font-size:0.72rem; color:#888; margin-bottom:2px; }
  .notif-box .dismiss-alert { border: none; background: transparent; color: #888; padding: 6px; }
  .notif-box .dismiss-alert:hover { color:#e3342f; cursor:pointer; }

  .notif-box .dropdown-footer { padding: 8px; border-top:1px solid rgba(0,0,0,0.03); text-align:center; }
</style>

@push('scripts')
<script>
(function(){
  const alertsApi = "/api/stock/alerts"; // GET
  const markSingleUrl = id => `/api/stock/alerts/${id}/read`; // POST
  const markAllUrl = "/api/stock/alerts/mark-read"; // POST (may 404 if absent)
  const stockPageBase = "{{ url('/inventory/stock') }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

  const bellAnchor = document.getElementById('notifDropdown');

  function ensureBadge() {
    if (!bellAnchor) return null;
    let badge = bellAnchor.querySelector('.notification-badge');
    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'notification-badge';
      bellAnchor.appendChild(badge);
    }
    return badge;
  }

  function ensureDropdownContainer() {
    if (!bellAnchor) return null;
    const parent = bellAnchor.parentElement;
    let box = parent.querySelector('.notif-box');
    if (!box) {
      box = document.createElement('ul');
      box.className = 'dropdown-menu notif-box animated fadeIn';
      box.style.right = '0';
      box.style.left = 'auto';
      parent.appendChild(box);
      bellAnchor.setAttribute('data-bs-toggle', 'dropdown');
      bellAnchor.setAttribute('aria-haspopup', 'true');
      bellAnchor.setAttribute('aria-expanded', 'false');
      parent.classList.add('dropdown', 'topbar-icon');
    }
    return box;
  }

  function normalizeAlertsPayload(payload) {
    if (!payload) return [];
    if (Array.isArray(payload)) return payload;
    if (payload.alerts && Array.isArray(payload.alerts)) return payload.alerts;
    if (payload.data && Array.isArray(payload.data)) return payload.data;
    return [];
  }

  function safeImageUrl(a) {
    // common fields: image, image_url, product.image
    if (!a) return null;
    if (a.image_url) return a.image_url;
    if (a.image) return a.image;
    if (a.product && (a.product.image || a.product.image_url)) return a.product.image || a.product.image_url;
    return null;
  }

  function productNameFromAlert(a) {
    return a.product_name || a.productName || (a.product && (a.product.name || a.product.title)) || (a.message && String(a.message).split('\n')[0]) || 'Product';
  }

  function escapeHtml(str) {
    if (!str && str !== 0) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }

  function updateBadgeAndClass(count) {
    const badge = ensureBadge();
    if (badge) badge.textContent = count > 0 ? String(count) : '';
    if (!bellAnchor) return;
    if (count > 0) bellAnchor.classList.add('has-alerts'); else bellAnchor.classList.remove('has-alerts');
  }

  async function renderDropdown(alerts) {
    const box = ensureDropdownContainer();
    if (!box) return;
    const headerHtml = `<li><div class="dropdown-title">Stock Alerts (${alerts.length})</div></li>`;
    let listHtml = '';
    if (alerts.length > 0) {
      listHtml += '<li><div class="notif-scroll">';
      alerts.forEach(a => {
        const id = a.id ?? a.alert_id ?? '';
        const pname = productNameFromAlert(a);
        const created = a.created_at ?? a.created ?? a.createdAt ?? '';
        const priority = a.priority ?? a.level ?? 'info';
        const message = (a.message && String(a.message).length > 0) ? a.message : `Check ${pname}`;
        const colorClass = priority === 'critical' ? 'text-danger' : (priority === 'high' ? 'text-warning' : 'text-muted');
        const img = safeImageUrl(a);
        const imgTag = img ? `<div style="flex:0 0 44px;"><img src="${escapeHtml(img)}" alt="${escapeHtml(pname)}" style="width:44px;height:44px;border-radius:6px;object-fit:cover;border:1px solid rgba(0,0,0,.06)"></div>` : `<div style="width:44px; text-align:center; flex: 0 0 44px;"><i class="fas fa-box ${colorClass}" style="font-size:20px"></i></div>`;
        listHtml += `
          <a href="${stockPageBase}?search=${encodeURIComponent(pname)}" class="dropdown-item" data-alert-id="${id}">
            ${imgTag}
            <div style="flex:1; min-width:0;">
              <div class="notif-meta">${created ? escapeHtml(created) : ''}</div>
              <div class="fw-bold" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(pname)}</div>
              <div class="notif-message">${escapeHtml(message)}</div>
            </div>
            <div style="flex:0 0 36px; text-align:right;">
              <button class="dismiss-alert btn btn-sm" data-id="${id}" aria-label="Dismiss"><i class="fas fa-times"></i></button>
            </div>
          </a>
        `;
      });
      listHtml += '</div></li>';
    } else {
      listHtml = `<li><div class="p-3 text-center text-muted">No alerts</div></li>`;
    }
    const footerHtml = `<li class="dropdown-footer"><button id="markAllReadBtn" class="btn btn-sm btn-link">Mark all read</button></li>`;
    box.innerHTML = headerHtml + listHtml + footerHtml;
  }

  // show simple toast for errors/info
  function showToast(message, type='info') {
    const t = document.createElement('div');
    t.className = `alert alert-${type} position-fixed`;
    t.style.cssText = 'top:20px; right:20px; z-index:4000; min-width:240px;';
    t.innerHTML = `${escapeHtml(message)} <button type="button" class="btn-close" aria-label="Close" style="float:right;"></button>`;
    document.body.appendChild(t);
    t.querySelector('.btn-close').addEventListener('click', ()=>t.remove());
    setTimeout(()=>{ if(t.parentElement) t.remove(); }, 4500);
  }

  // Mark single optimistic: remove DOM entry & decrement badge immediately, then POST.
  async function markSingleAndRemove(id, anchorElem) {
    if (!id) return false;
    // optimistic UI snapshot for rollback
    const badge = ensureBadge();
    const prevBadge = badge ? (Number(badge.textContent) || 0) : 0;
    const prevHTML = anchorElem ? anchorElem.outerHTML : null;
    // remove UI immediately
    if (anchorElem && anchorElem.parentElement) {
      anchorElem.remove();
    }
    updateBadgeAndClass(Math.max(0, prevBadge - 1));

    try {
      const res = await fetch(markSingleUrl(id), {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'X-CSRF-TOKEN': csrfToken || '',
          'Accept': 'application/json'
        }
      });
      if (res.ok || res.status === 204) {
        // success - leave optimistic update
        return true;
      }
      // attempt parse JSON for success flag
      let json = null;
      try { json = await res.json(); } catch(e){}
      if (json && (json.success || json.ok)) return true;

      // failure: rollback UI
      if (prevHTML && ensureDropdownContainer()) {
        const box = ensureDropdownContainer();
        // put item back at top of scroll area
        const scrollContainer = box.querySelector('.notif-scroll') || box;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = prevHTML;
        scrollContainer.insertBefore(wrapper.firstElementChild, scrollContainer.firstChild);
        updateBadgeAndClass(prevBadge);
      } else {
        updateBadgeAndClass(prevBadge);
      }
      showToast('Failed to mark alert read (server error).', 'danger');
      return false;
    } catch (err) {
      // network or other error: rollback UI
      if (prevHTML && ensureDropdownContainer()) {
        const box = ensureDropdownContainer();
        const scrollContainer = box.querySelector('.notif-scroll') || box;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = prevHTML;
        scrollContainer.insertBefore(wrapper.firstElementChild, scrollContainer.firstChild);
      }
      updateBadgeAndClass(prevBadge);
      showToast('Network error while marking alert. Check console.', 'danger');
      console.error('markSingleAndRemove error', err);
      return false;
    }
  }

  async function markAllRead() {
    try {
      const res = await fetch(markAllUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
      });
      if (res.ok || res.status === 204) {
        updateBadgeAndClass(0);
        const box = ensureDropdownContainer();
        if (box) box.innerHTML = `<li><div class="dropdown-title">Stock Alerts (0)</div></li><li><div class="p-3 text-center text-muted">No alerts</div></li>`;
      } else {
        showToast('Mark all read failed (server).', 'warning');
      }
    } catch (err) {
      showToast('Network error marking all read', 'danger');
    }
  }

  // delegated clicks
  document.addEventListener('click', async function(e) {
    const dismiss = e.target.closest('.dismiss-alert');
    if (dismiss) {
      e.preventDefault();
      const id = dismiss.dataset.id;
      const anchorElem = dismiss.closest('.dropdown-item');
      await markSingleAndRemove(id, anchorElem);
      return;
    }
    if (e.target && e.target.id === 'markAllReadBtn') {
      e.preventDefault();
      await markAllRead();
      return;
    }
    const alertAnchor = e.target.closest('.dropdown-item');
    if (alertAnchor && alertAnchor.dataset && alertAnchor.dataset.alertId) {
      if (e.button === 0 && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
        e.preventDefault();
        const id = alertAnchor.dataset.alertId;
        const href = alertAnchor.href;
        await markSingleAndRemove(id, alertAnchor);
        // small delay for UI update
        setTimeout(()=>{ window.location.href = href; }, 120);
      }
      return;
    }
  });

  async function loadAlerts() {
    try {
      const res = await fetch(alertsApi, { credentials: 'same-origin', headers: { Accept: 'application/json' }});
      if (!res.ok) { console.warn('Failed to fetch alerts', res.status); return; }
      const json = await res.json();
      const alerts = normalizeAlertsPayload(json);
      updateBadgeAndClass(alerts.length);
      await renderDropdown(alerts);
    } catch (err) {
      console.error('Error loading alerts', err);
    }
  }

  // start
  loadAlerts();
  setInterval(loadAlerts, 30000);
})();
</script>
@endpush