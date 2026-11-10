<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA IVF — Reproductive Medicine & Assisted Conception System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* PURE MONOCHROME LIGHT THEME: WHITE BG, BLACK FONTS, ZERO OTHER COLORS */
        :root {
            --bg-page: #ffffff;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --bg-hover: #f4f4f5;
            --bg-active: #000000;
            --fg-active: #ffffff;
            --text-primary: #000000;
            --text-secondary: #3f3f46;
            --text-muted: #71717a;
            --border-light: #e4e4e7;
            --border-strong: #000000;
            --sidebar-width: 290px;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-family: var(--font-sans);
            font-size: 13px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        a {
            color: var(--text-primary);
            text-decoration: none;
        }

        /* LEFT SIDEBAR NAVIGATION */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-strong);
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
            z-index: 40;
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-strong);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: #ffffff;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background: #000000;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .brand-badge {
            font-size: 9px;
            font-family: var(--font-mono);
            font-weight: 600;
            padding: 1px 5px;
            border: 1px solid var(--border-strong);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 2px;
        }

        .sidebar-menu {
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
        }

        .menu-category {
            font-size: 10px;
            font-family: var(--font-mono);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            color: var(--text-muted);
            padding: 0.75rem 0.75rem 0.35rem 0.75rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.75rem;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .menu-item-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .menu-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            stroke: currentColor;
            flex-shrink: 0;
        }

        .menu-item:hover {
            color: #000000;
            background: var(--bg-hover);
            border-color: var(--border-light);
        }

        .menu-item.active {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
            font-weight: 600;
        }

        .menu-badge {
            font-size: 11px;
            font-family: var(--font-mono);
            padding: 1px 6px;
            border: 1px solid var(--border-light);
            background: #f4f4f5;
            color: #000000;
            font-weight: 600;
        }

        .menu-item.active .menu-badge {
            background: #ffffff;
            color: #000000;
            border-color: #ffffff;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-strong);
            background: #fafafa;
            font-size: 11px;
            font-family: var(--font-mono);
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #000000;
            border-radius: 50%;
        }

        /* RIGHT MAIN CONTENT AREA */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg-page);
        }

        .top-bar {
            height: 52px;
            border-bottom: 1px solid var(--border-strong);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .top-bar-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .top-bar-meta {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 11px;
            font-family: var(--font-mono);
        }

        .content-area {
            padding: 2rem;
            max-width: 1440px;
            width: 100%;
        }

        /* METRICS STRIP */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 12px;
            background: transparent;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid var(--border-strong);
            padding: 1.1rem 1.35rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .metric-label {
            font-size: 11px;
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
            font-family: var(--font-mono);
            letter-spacing: -1px;
        }

        .metric-sub {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* PANELS & TABLES */
        .panel {
            background: #ffffff;
            border: 1px solid var(--border-strong);
            margin-bottom: 2rem;
        }

        .panel-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-strong);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .panel-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .panel-desc {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .panel-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: var(--font-sans);
            text-decoration: none;
            border-radius: 0px;
        }

        .btn-black {
            background: #000000;
            color: #ffffff;
            border: 1px solid #000000;
        }

        .btn-black:hover {
            background: #27272a;
        }

        .btn-outline {
            background: #ffffff;
            color: #000000;
            border: 1px solid var(--border-strong);
        }

        .btn-outline:hover {
            background: #f4f4f5;
        }

        .btn-sm {
            padding: 0.3rem 0.65rem;
            font-size: 11px;
        }

        /* TABLES */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table.clinical-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 12.5px;
        }

        table.clinical-table th {
            padding: 0.7rem 1.1rem;
            background: #f4f4f5;
            font-size: 10.5px;
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            border-bottom: 1px solid var(--border-strong);
            color: #000000;
        }

        table.clinical-table td {
            padding: 0.8rem 1.1rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        table.clinical-table tr:last-child td {
            border-bottom: none;
        }

        table.clinical-table tr:hover td {
            background: #fafafa;
        }

        /* TAGS & PILLS (MONOCHROME) */
        .tag {
            display: inline-block;
            font-family: var(--font-mono);
            font-size: 10.5px;
            font-weight: 600;
            padding: 2px 6px;
            border: 1px solid var(--border-strong);
            white-space: nowrap;
        }

        .tag-black {
            background: #000000;
            color: #ffffff;
        }

        .tag-outline {
            background: #ffffff;
            color: #000000;
        }

        /* FORM ELEMENTS */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            padding: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .form-label {
            font-size: 11px;
            font-family: var(--font-mono);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
        }

        .form-control {
            padding: 0.55rem 0.75rem;
            font-size: 13px;
            border: 1px solid var(--border-strong);
            background: #ffffff;
            color: #000000;
            font-family: var(--font-sans);
            outline: none;
        }

        .form-control:focus {
            outline: 2px solid #000000;
        }

        textarea.form-control {
            min-height: 70px;
            resize: vertical;
        }

        /* FLASH ALERTS */
        .alert-box {
            background: #ffffff;
            border: 1px solid var(--border-strong);
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* TIMELINE CARD */
        .timeline-step {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 1.1rem;
            border-left: 2px solid var(--border-strong);
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 3px;
            width: 8px;
            height: 8px;
            background: #000000;
        }

        .timeline-title {
            font-size: 12.5px;
            font-weight: 700;
        }

        .timeline-body {
            font-size: 11.5px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        /* MODAL STYLES (MONOCHROME) */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1.5rem;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-dialog {
            background: #ffffff;
            border: 2px solid var(--border-strong);
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-strong);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f4f4f5;
        }

        .modal-title {
            font-size: 14px;
            font-weight: 700;
            font-family: var(--font-sans);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            padding: 0 4px;
            line-height: 1;
        }

        .modal-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-strong);
            background: #f4f4f5;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* UTILITY */
        .mono {
            font-family: var(--font-mono);
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: 700;
        }

        .mb-2 { margin-bottom: 0.5rem; }
    </style>
</head>
<body>

    <!-- LEFT SIDEBAR WITH ICONS & BADGE COUNTS -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">A</div>
            <div>
                <div class="brand-title">AURA IVF</div>
                <div class="brand-badge">CLINICAL OS</div>
            </div>
        </div>

        @php
            $u = Auth::user();
            $showMain = !$u || $u->canAccessTab('overview') || $u->canAccessTab('leads') || $u->canAccessTab('couples');
            $showTreatment = !$u || $u->canAccessTab('cycles') || $u->canAccessTab('laboratory') || $u->canAccessTab('transfers') || $u->canAccessTab('failures');
            $showMaternity = !$u || $u->canAccessTab('antenatal') || $u->canAccessTab('deliveries') || $u->canAccessTab('pediatrics') || $u->canAccessTab('cryobank');
            $showAdmin = !$u || $u->canAccessTab('settings');
        @endphp

        <nav class="sidebar-menu">
            @if($showMain)
            <div class="menu-category">MAIN SYSTEM</div>
            
            @if(!$u || $u->canAccessTab('overview'))
            <!-- 1. Overview & Metrics -->
            <a href="{{ route('ivf.home', ['tab' => 'overview']) }}" class="menu-item {{ $activeTab === 'overview' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </span>
                    <span>Overview & Metrics</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('leads'))
            <!-- Enquiries & CRM -->
            <a href="{{ route('ivf.home', ['tab' => 'leads']) }}" class="menu-item {{ $activeTab === 'leads' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </span>
                    <span>Enquiries & CRM</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('couples'))
            <!-- Couples & Workup -->
            <a href="{{ route('ivf.home', ['tab' => 'couples']) }}" class="menu-item {{ $activeTab === 'couples' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                    <span>Couples & Workup</span>
                </div>
            </a>
            @endif
            @endif

            @if($showTreatment)
            <div class="menu-category">TREATMENT & LAB</div>

            @if(!$u || $u->canAccessTab('cycles'))
            <!-- Stimulation & Folliculometry -->
            <a href="{{ route('ivf.home', ['tab' => 'cycles']) }}" class="menu-item {{ $activeTab === 'cycles' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </span>
                    <span>Stimulation & Folliculometry</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('laboratory'))
            <!-- OPU & Embryology Lab -->
            <a href="{{ route('ivf.home', ['tab' => 'laboratory']) }}" class="menu-item {{ $activeTab === 'laboratory' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="4"></circle>
                            <line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line>
                            <line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line>
                        </svg>
                    </span>
                    <span>OPU & Embryology Lab</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('transfers'))
            <!-- Embryo Transfers & Beta-hCG -->
            <a href="{{ route('ivf.home', ['tab' => 'transfers']) }}" class="menu-item {{ $activeTab === 'transfers' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                        </svg>
                    </span>
                    <span>Embryo Transfers & Beta-hCG</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('failures'))
            <!-- Failure RCA & Re-attempts -->
            <a href="{{ route('ivf.home', ['tab' => 'failures']) }}" class="menu-item {{ $activeTab === 'failures' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <polyline points="23 20 23 14 17 14"></polyline>
                            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                        </svg>
                    </span>
                    <span>Failure RCA & Re-attempts</span>
                </div>
            </a>
            @endif
            @endif

            @if($showMaternity)
            <div class="menu-category">MATERNITY & BIO-BANK</div>

            @if(!$u || $u->canAccessTab('antenatal'))
            <!-- Antenatal Care (ANC) -->
            <a href="{{ route('ivf.home', ['tab' => 'antenatal']) }}" class="menu-item {{ $activeTab === 'antenatal' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </span>
                    <span>Antenatal Care (ANC)</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('deliveries'))
            <!-- Labor & Delivery -->
            <a href="{{ route('ivf.home', ['tab' => 'deliveries']) }}" class="menu-item {{ $activeTab === 'deliveries' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="M7 15h10"></path>
                            <path d="M12 9v6"></path>
                        </svg>
                    </span>
                    <span>Labor & Delivery</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('pediatrics'))
            <!-- Pediatrics & Vaccinations -->
            <a href="{{ route('ivf.home', ['tab' => 'pediatrics']) }}" class="menu-item {{ $activeTab === 'pediatrics' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M18 2l4 4"></path>
                            <path d="M17 7l3-3"></path>
                            <path d="M19 9l-8.5 8.5a2.12 2.12 0 0 1-3 0l-2-2a2.12 2.12 0 0 1 0-3L14 5"></path>
                            <path d="M5 19l-3 3"></path>
                            <path d="M14 10l-4 4"></path>
                        </svg>
                    </span>
                    <span>Pediatrics & Vaccinations</span>
                </div>
            </a>
            @endif

            @if(!$u || $u->canAccessTab('cryobank'))
            <!-- Cryobank & Bio-Repository -->
            <a href="{{ route('ivf.home', ['tab' => 'cryobank']) }}" class="menu-item {{ $activeTab === 'cryobank' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <line x1="12" y1="2" x2="12" y2="22"></line>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                            <line x1="19.07" y1="4.93" x2="4.93" y2="19.07"></line>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                    <span>Cryobank &amp; Bio-Repository</span>
                </div>
            </a>
            @endif
            @endif

            @if($showAdmin)
            <div class="menu-category">ADMINISTRATION</div>

            @if(!$u || $u->canAccessTab('settings'))
            <!-- Settings & Access Control -->
            <a href="{{ route('ivf.home', ['tab' => 'settings']) }}" class="menu-item {{ $activeTab === 'settings' ? 'active' : '' }}">
                <div class="menu-item-left">
                    <span class="menu-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </span>
                    <span>Settings &amp; Access Control</span>
                </div>
            </a>
            @endif
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="status-pill">
                <span class="status-dot"></span>
                <span>AURA REPRODUCTIVE MEDICINE</span>
            </div>
        </div>
    </aside>

    <!-- RIGHT MAIN WORKSPACE -->
    <div class="main-wrapper">
        <header class="top-bar" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="top-bar-title">
                <span>{{ strtoupper(str_replace('_', ' ', $activeTab)) }}</span>
            </div>

            <div class="top-bar-meta" style="display: flex; align-items: center; gap: 14px;">
                <span>{{ date('l, d F Y') }}</span>
                <span>|</span>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="text-align: right; line-height: 1.25;">
                        <div style="font-weight: 700; font-size: 11.5px; color: #000000;">
                            {{ Auth::user()->name ?? 'Dr. Elena Vance, MD' }}
                        </div>
                        <div class="text-muted mono" style="font-size: 10px;">
                            {{ Auth::user()->role ?? 'Super Admin / Medical Director' }}
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" style="font-size: 11px; padding: 4px 9px; border-width: 1.5px;" title="Sign out of workstation">
                            Sign Out &rarr;
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert-box">
                    <div>
                        <strong>SUCCESS:</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box" style="border-color: #000000; background: #fafafa;">
                    <div>
                        <strong>VALIDATION NOTICE:</strong>
                        <ul style="margin-left: 1.25rem; margin-top: 0.25rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- NATIVE VANILLA JAVASCRIPT MODAL & SEARCH HELPERS  -->
    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('active');
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('active');
            }
        }

        // Live instant filter for select options with search
        function filterSelectOptions(selectId, query) {
            const select = document.getElementById(selectId);
            if (!select) return;
            const q = query.toLowerCase().trim();
            let matchCount = 0;
            
            const options = select.querySelectorAll('option');
            options.forEach(opt => {
                const searchData = (opt.getAttribute('data-search') || opt.textContent).toLowerCase();
                if (!q || searchData.includes(q)) {
                    opt.style.display = '';
                    opt.disabled = false;
                    matchCount++;
                } else {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            });

            // Auto-select first visible option if current selection is hidden
            const selectedOpt = select.options[select.selectedIndex];
            if (!selectedOpt || selectedOpt.style.display === 'none') {
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].style.display !== 'none' && !select.options[i].disabled) {
                        select.selectedIndex = i;
                        break;
                    }
                }
            }

            const countEl = document.getElementById(selectId + '_count');
            if (countEl) countEl.textContent = matchCount;
        }

        // Live table row search filter
        function filterTable(tbodyId, query) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;
            const q = query.toLowerCase().trim();
            const rows = tbody.querySelectorAll('tr');
            rows.forEach(tr => {
                const text = tr.innerText.toLowerCase();
                tr.style.display = (!q || text.includes(q)) ? '' : 'none';
            });
        }

        function openStartCycleForCouple(coupleId) {
            openModal('newCycleModal');
            const select = document.getElementById('cycle_couple_select');
            if (select) {
                select.value = coupleId;
            }
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    modal.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
