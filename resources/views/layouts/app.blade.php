<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $companySetting?->company_name ?? config('app.name'))</title>
    @if($companySetting?->company_logo)
        <link rel="icon" href="{{ asset('storage/'.$companySetting->company_logo) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/'.$companySetting->company_logo) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>{!! file_get_contents(resource_path('css/app.css')) !!}</style>
</head>
<body>
<svg aria-hidden="true" style="display:none">
    <symbol id="icon-dashboard" viewBox="0 0 24 24"><path d="M4 13h7V4H4v9Zm9 7h7V4h-7v16ZM4 20h7v-5H4v5Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-package" viewBox="0 0 24 24"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/><path d="M4.5 7.8 12 12l7.5-4.2M12 12v8.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-users" viewBox="0 0 24 24"><path d="M16 20c0-2.2-1.8-4-4-4H7c-2.2 0-4 1.8-4 4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/><circle cx="9.5" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20c0-1.8-1.1-3.3-2.7-3.8M16.5 4.4a4 4 0 0 1 0 7.2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-boxes" viewBox="0 0 24 24"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a4.5 4.5 0 0 0 5 5L10 21 3 14l9.7-9.7Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/><path d="m7 17 3-3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-chart" viewBox="0 0 24 24"><path d="M4 20V4M4 20h16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/><path d="M8 17v-5m4 5V8m4 9v-7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-wallet" viewBox="0 0 24 24"><path d="M4 7h15a2 2 0 0 1 2 2v9H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h13" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M17 13h4M17 13a1 1 0 1 0 0 .1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-settings" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.1 2.1-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-3v-.2a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-2.1-2.1.1-.1A1.7 1.7 0 0 0 5 15a1.7 1.7 0 0 0-1.6-1H3.2v-3h.2A1.7 1.7 0 0 0 5 10a1.7 1.7 0 0 0-.3-1.9L4.6 8l2.1-2.1.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6v-.2h3v.2a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L17.9 8l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v3h-.2a1.7 1.7 0 0 0-1.6 1Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-logout" viewBox="0 0 24 24"><path d="M10 17v2H4V5h6v2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M14 16l4-4-4-4M18 12H9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-sidebar" viewBox="0 0 24 24"><path d="M4 5h16v14H4V5Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/><path d="M9 5v14M15 9l-3 3 3 3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-eye" viewBox="0 0 24 24"><path d="M2.1 12s3.6-7 9.9-7 9.9 7 9.9 7-3.6 7-9.9 7-9.9-7-9.9-7Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/></symbol>
    <symbol id="icon-pencil" viewBox="0 0 24 24"><path d="M12 20h9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/><path d="m16.5 3.5 4 4L8 20H4v-4L16.5 3.5Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></symbol>
    <symbol id="icon-trash" viewBox="0 0 24 24"><path d="M3 6h18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/><path d="M8 6V4h8v2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="m6 6 1 15h10l1-15" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M10 11v5M14 11v5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-receipt" viewBox="0 0 24 24"><path d="M6 3h12v18l-2-1.2-2 1.2-2-1.2-2 1.2-2-1.2L6 21V3Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2"/><path d="M9 8h6M9 12h6M9 16h4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/></symbol>
    <symbol id="icon-cart" viewBox="0 0 24 24"><path d="M3 4h2l2.2 11h10.6L20 7H6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><circle cx="9" cy="20" r="1.5" fill="currentColor"/><circle cx="18" cy="20" r="1.5" fill="currentColor"/></symbol>
</svg>
<script>if(localStorage.getItem('sidebar-collapsed')==='1'){document.body.classList.add('sidebar-collapsed')}</script>
<div class="container-fluid app-shell">
    <div class="app-layout">
        <aside class="sidebar no-print">
            <div class="sidebar-brand p-3">
                <div class="brand-block d-flex align-items-center gap-2">
                    @if($companySetting?->company_logo)
                        <img class="brand-logo" src="{{ asset('storage/'.$companySetting->company_logo) }}" alt="Logo">
                    @else
                        <span class="brand-mark">{{ strtoupper(substr($companySetting?->company_name ?? config('app.name', 'S'), 0, 1)) }}</span>
                    @endif
                    <strong class="brand-name nav-label">{{ $companySetting?->company_name ?? config('app.name', 'Stock Management') }}</strong>
                </div>
                <button class="sidebar-toggle" type="button" title="Collapse sidebar" aria-label="Collapse sidebar" aria-expanded="true"><svg><use href="#icon-sidebar"></use></svg></button>
            </div>
            <nav class="nav-menu d-grid gap-1">
                <a class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" title="Dashboard"><svg class="nav-icon"><use href="#icon-dashboard"></use></svg><span class="nav-label">Dashboard</span></a>
                <a class="nav-link-item {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}" title="Items"><svg class="nav-icon"><use href="#icon-package"></use></svg><span class="nav-label">Items</span></a>
                <a class="nav-link-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}" title="Suppliers"><svg class="nav-icon"><use href="#icon-users"></use></svg><span class="nav-label">Suppliers</span></a>
                <a class="nav-link-item {{ request()->routeIs('stock-entries.*') ? 'active' : '' }}" href="{{ route('stock-entries.index') }}" title="Stock Entries"><svg class="nav-icon"><use href="#icon-boxes"></use></svg><span class="nav-label">Stock Entries</span></a>
                <a class="nav-link-item {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}" title="Sales"><svg class="nav-icon"><use href="#icon-cart"></use></svg><span class="nav-label">Sales</span></a>
                <a class="nav-link-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}" title="Expenses"><svg class="nav-icon"><use href="#icon-wallet"></use></svg><span class="nav-label">Expenses</span></a>
                <a class="nav-link-item {{ request()->routeIs('repair-services.*') || request()->routeIs('service-orders.*') ? 'active' : '' }}" href="{{ route('repair-services.index') }}" title="Repair / Services"><svg class="nav-icon"><use href="#icon-wrench"></use></svg><span class="nav-label">Repair / Services</span></a>
                <a class="nav-link-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.sales') }}" title="Reports"><svg class="nav-icon"><use href="#icon-chart"></use></svg><span class="nav-label">Reports</span></a>
                <a class="nav-link-item {{ request()->routeIs('company-settings.*') ? 'active' : '' }}" href="{{ route('company-settings.edit') }}" title="Company Settings"><svg class="nav-icon"><use href="#icon-settings"></use></svg><span class="nav-label">Company Settings</span></a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link-item w-100 border-0 bg-transparent" type="submit" title="Logout"><svg class="nav-icon"><use href="#icon-logout"></use></svg><span class="nav-label">Logout</span></button>
                </form>
            </nav>
        </aside>
        <main class="page-main">
            <div class="topbar d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h4 mb-0">@yield('page_title', 'Dashboard')</h1>
                    @if($companySetting?->phone || $companySetting?->email)
                        <small class="text-muted">{{ $companySetting->phone }} {{ $companySetting->email ? ' | '.$companySetting->email : '' }}</small>
                    @endif
                </div>
                <div class="no-print">@yield('actions')</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')

            <footer class="text-center text-muted small mt-4">
                {{ $companySetting?->footer_text ?? 'Local stock management system' }}
            </footer>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebarToggle=document.querySelector('.sidebar-toggle');
function syncSidebarToggle(){const collapsed=document.body.classList.contains('sidebar-collapsed');sidebarToggle?.setAttribute('aria-expanded',(!collapsed).toString());sidebarToggle?.setAttribute('title',collapsed?'Expand sidebar':'Collapse sidebar');sidebarToggle?.setAttribute('aria-label',collapsed?'Expand sidebar':'Collapse sidebar')}
sidebarToggle?.addEventListener('click',()=>{document.body.classList.toggle('sidebar-collapsed');localStorage.setItem('sidebar-collapsed',document.body.classList.contains('sidebar-collapsed')?'1':'0');syncSidebarToggle()});
syncSidebarToggle();
</script>
@stack('scripts')
</body>
</html>
