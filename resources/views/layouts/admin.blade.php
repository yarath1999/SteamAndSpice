<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Steam & Spice</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800;900&display=swap');

        :root {
            --bg: #1a120b;
            --ink: #f5f5f5;
            --accent: #ff7b32;
            --accent-2: #f1c876;
            --line: rgba(241, 200, 118, 0.16);
            --panel: #2a1c13;
            --sidebar-bg: #120903;
            --sidebar-link: #cfcfcf;
            --sidebar-link-active: #f5f5f5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 18%, rgba(255, 123, 50, 0.08) 0%, rgba(255, 123, 50, 0) 34%),
                linear-gradient(180deg, #1a120b 0%, #2b1d14 100%);
            line-height: 1.6;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.3px;
            margin: 0 0 16px;
        }
        h1 {
            font-size: 2rem;
        }
        h2 {
            font-size: 1.5rem;
        }
        h3 {
            font-size: 1.2rem;
        }
        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px 1fr;
        }
        .sidebar {
            background: var(--sidebar-bg);
            color: #f5f5f5;
            padding: 24px 16px;
        }
        .brand {
            color: #f5f5f5;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.15rem;
            display: block;
            margin-bottom: 22px;
            font-family: 'Playfair Display', Georgia, serif;
        }
        .nav-group {
            display: grid;
            gap: 6px;
        }
        .nav-link {
            text-decoration: none;
            color: var(--sidebar-link);
            padding: 10px 12px;
            border-radius: 9px;
            font-weight: 600;
            font-family: 'Jost', 'Segoe UI', sans-serif;
        }
        .nav-link:hover {
            background: rgba(255, 123, 50, 0.12);
            color: var(--sidebar-link-active);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(255, 123, 50, 0.22), rgba(241, 200, 118, 0.16));
            color: var(--sidebar-link-active);
        }
        .sidebar-actions {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid rgba(241, 200, 118, 0.14);
            display: grid;
            gap: 8px;
        }
        .content {
            padding: 28px;
        }
        .content-wrap {
            width: min(1280px, 100%);
            margin: 0 auto;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 24px;
        }
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 20px; 
        }
        .form-group { 
            margin-bottom: 18px; 
        }
        label { 
            display: block; 
            font-weight: 700; 
            margin-bottom: 8px;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 0.95rem;
        }
        input, textarea, select {
            width: 100%;
            border: 1px solid rgba(241, 200, 118, 0.18);
            border-radius: 8px;
            padding: 9px;
            background: rgba(255, 255, 255, 0.04);
            color: #f5f5f5;
            font-family: 'Jost', 'Segoe UI', sans-serif;
        }
        .btn {
            border: 0;
            display: inline-block;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #1a120b;
            padding: 9px 13px;
            border-radius: 9px;
            cursor: pointer;
            text-decoration: none;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            font-weight: 600;
        }
        .btn-muted { background: linear-gradient(180deg, #342318, #2a1c13); color: #f5f5f5; }
        .admin-panel .cta-btn {
            box-shadow: none;
            transform: none;
        }
        .admin-panel .cta-btn:hover {
            transform: none;
            box-shadow: none;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid var(--line); padding: 8px; text-align: left; font-family: 'Jost', 'Segoe UI', sans-serif; }
        th { font-weight: 700; }
        .flash { margin: 14px 0; padding: 10px 12px; border-radius: 9px; }
        .ok { background: rgba(255, 123, 50, 0.08); border: 1px solid rgba(241, 200, 118, 0.18); color: #f5f5f5; }
        .bad { background: rgba(255, 123, 50, 0.12); border: 1px solid rgba(255, 123, 50, 0.22); color: #f5f5f5; }
        @media (max-width: 900px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }
            .sidebar {
                padding: 16px;
            }
            .nav-group {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body class="admin-panel">
<div class="admin-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}">Steam & Spice Admin</a>

        <nav class="nav-group">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}" href="{{ route('admin.menu-items.index') }}">Menu Management</a>
            <a class="nav-link {{ request()->routeIs('admin.updates.*') ? 'active' : '' }}" href="{{ route('admin.updates.index') }}">Updates</a>
            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">Site Settings</a>
            <a class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}" href="{{ route('admin.about.edit') }}">About Us</a>
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
            <a class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}" href="{{ route('admin.homepage.edit') }}">Homepage Settings</a>
        </nav>

        <div class="sidebar-actions">
            <a class="btn btn-muted cta-btn" href="{{ route('home') }}">View Public Site</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn cta-btn" style="width: 100%;" type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <main class="content">
        <div class="content-wrap">
            @if(session('success'))
                <div class="flash ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash bad">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="flash bad">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    </div>
</body>
@stack('scripts')
</html>
