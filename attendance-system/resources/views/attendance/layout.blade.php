<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Workforce Attendance & Analytics v2.4')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Immediately apply saved theme to avoid flash
        (function() {
            var savedTheme = localStorage.getItem('workforce_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        /* COLOR COORDINATION SYSTEM (VIDEO MATCH: CYAN / TEAL / OBSIDIAN) */
        :root {
            --cyan: #06b6d4;
            --cyan-hover: #0891b2;
            --cyan-glow: rgba(6, 182, 212, 0.35);
            --electric-blue: #0ea5e9;
            --teal: #14b8a6;
            --emerald: #10b981;
            --coral: #ef4444;
            --orange: #f97316;
            --purple: #8b5cf6;
            
            /* DARK MODE (DEFAULT - MATCHING VIDEO FRAMES 00:00 - 00:02) */
            --bg: #0b1329;
            --sidebar-bg: #070d1e;
            --sidebar-active: #06b6d4;
            --sidebar-text: #94a3b8;
            --card-bg: #111d35;
            --card-header-bg: #132240;
            --border: #1e293b;
            --border-hover: #334155;
            --text-dark: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #06b6d4;
            --primary-hover: #0891b2;
            --table-head-bg: #0d172c;
            --table-row-hover: rgba(6, 182, 212, 0.08);
            --table-border: #1a2744;
            --input-bg: #0b1428;
            --input-border: #1e293b;
            --input-text: #f1f5f9;
            --input-focus: #06b6d4;
            --topbar-bg: #0c162d;
            --topbar-border: #1a2744;
            --badge-in-bg: #064e3b;
            --badge-in-text: #34d399;
            --badge-out-bg: #7f1d1d;
            --badge-out-text: #f87171;
            --tab-active-bg: rgba(6, 182, 212, 0.15);
            --tab-active-text: #06b6d4;
            --tab-active-border: #06b6d4;
        }

        /* LIGHT MODE (MATCHING VIDEO FRAMES 00:03 - 00:09) */
        [data-theme="light"] {
            --bg: #f4f6fa;
            --sidebar-bg: #0b1329;
            --sidebar-active: #0284c7;
            --sidebar-text: #94a3b8;
            --card-bg: #ffffff;
            --card-header-bg: #f8fafc;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --table-head-bg: #f8fafc;
            --table-row-hover: #f1f5f9;
            --table-border: #f1f5f9;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-text: #0f172a;
            --input-focus: #0284c7;
            --topbar-bg: #ffffff;
            --topbar-border: #e2e8f0;
            --badge-in-bg: #dcfce7;
            --badge-in-text: #15803d;
            --badge-out-bg: #fee2e2;
            --badge-out-text: #b91c1c;
            --tab-active-bg: #eff6ff;
            --tab-active-text: #0284c7;
            --tab-active-border: #0284c7;
        }

        * { box-sizing: border-box; }
        html {
            scroll-behavior: smooth;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text-dark);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 13.5px;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR (VIDE0-PERFECT NAVY & CYAN GLOW) */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), left 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(11, 19, 41, 0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 990;
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px 24px;
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .sidebar-brand .brand-logo-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(6, 182, 212, 0.5);
            flex-shrink: 0;
        }

        .sidebar-brand .brand-name {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .sidebar-brand .brand-title {
            font-weight: 800;
            font-size: 16px;
            color: #ffffff;
            letter-spacing: -0.2px;
        }

        .sidebar-brand .brand-badge {
            font-size: 10px;
            font-weight: 700;
            color: #06b6d4;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-section {
            padding: 0 14px 16px;
        }

        .nav-header {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #475569;
            text-transform: uppercase;
            padding: 14px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 4px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            color: inherit;
            transition: transform 0.2s ease;
        }

        .nav-item:hover {
            color: #ffffff;
            background: rgba(6, 182, 212, 0.1);
            transform: translateX(3px);
        }

        .nav-item:hover svg {
            color: #06b6d4;
            transform: scale(1.1);
        }

        .nav-item.active {
            background: linear-gradient(90deg, #06b6d4 0%, #0284c7 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.4);
        }

        .nav-item.active svg {
            color: #ffffff;
        }

        /* MAIN CONTENT AREA */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* TOPBAR (VIDE0-PERFECT ACTIONS & COUNTDOWN) */
        .topbar {
            height: 64px;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 40;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-title .version-pill {
            font-size: 11px;
            font-weight: 700;
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.12);
            padding: 2px 7px;
            border-radius: 6px;
            border: 1px solid rgba(6, 182, 212, 0.25);
        }

        .menu-toggle {
            background: none;
            border: 0;
            cursor: pointer;
            padding: 6px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: background 0.18s ease;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* AUTO REFRESH TOGGLE PILL (EXACT TO VIDEO 00:00 & 00:07) */
        .auto-refresh-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            padding: 5px 12px 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            user-select: none;
            transition: all 0.2s ease;
        }

        .auto-refresh-pill:hover {
            border-color: var(--border-hover);
        }

        .refresh-label {
            font-size: 12px;
            color: var(--text-dark);
            font-weight: 600;
        }

        .refresh-info {
            font-size: 11px;
            color: var(--text-muted);
            cursor: help;
        }

        /* TOGGLE SWITCH COMPONENT */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 32px;
            height: 18px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #475569;
            transition: .25s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 20px;
        }

        .toggle-switch .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .25s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 50%;
        }

        .toggle-switch input:checked + .slider {
            background-color: #06b6d4;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
        }

        .toggle-switch input:checked + .slider:before {
            transform: translateX(14px);
        }

        .countdown-badge {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 11px;
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.12);
            padding: 2px 6px;
            border-radius: 4px;
            min-width: 28px;
            text-align: center;
        }

        /* TOPBAR ICON BUTTONS & THEME TOGGLE */
        .icon-btn {
            background: var(--card-bg);
            border: 1px solid var(--border);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            cursor: pointer;
            position: relative;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .icon-btn:hover {
            color: var(--text-dark);
            border-color: var(--cyan);
            transform: translateY(-1px) scale(1.05);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.25);
        }

        .icon-btn .badge-dot {
            width: 7px;
            height: 7px;
            background: var(--coral);
            border-radius: 50%;
            position: absolute;
            top: 7px;
            right: 8px;
            border: 1.5px solid var(--card-bg);
        }

        /* USER PROFILE PILL */
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px 4px 4px;
            border-radius: 30px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            border: 1px solid var(--border);
            background: var(--card-bg);
        }

        .user-pill:hover {
            border-color: var(--cyan);
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            border: 1.5px solid var(--card-bg);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name {
            font-weight: 700;
            font-size: 12.5px;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 10.5px;
            color: var(--text-muted);
        }

        /* UNIFIED DUAL TAB NAVIGATION (EXACT TO VIDEO 00:00 - 00:06) */
        .report-tabs-container {
            margin-bottom: 18px;
        }

        .report-tabs-bar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--card-bg);
            padding: 6px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 100%;
            overflow-x: auto;
        }

        .report-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }

        .report-tab-btn:hover {
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.04);
        }

        .report-tab-btn.active {
            color: var(--tab-active-text);
            background: var(--tab-active-bg);
            border: 1px solid var(--tab-active-border);
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.15);
        }

        .report-tab-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            color: var(--text-muted);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
            user-select: none;
        }

        .report-tab-add:hover {
            color: var(--cyan);
            background: rgba(6, 182, 212, 0.1);
        }

        .report-tab-hint {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 6px;
            padding-left: 4px;
        }

        /* PAGE CONTENT */
        .content {
            padding: 24px 28px;
            flex: 1;
        }

        /* CARD CONTAINERS */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover {
            border-color: var(--border-hover);
        }

        /* BUTTONS (CYAN ACCENTS) */
        .btn {
            background: var(--primary);
            color: #ffffff;
            border: 0;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1.5px);
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.45);
            color: #ffffff;
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-outline {
            background: var(--card-bg);
            color: var(--text-dark);
            border: 1px solid var(--border);
            box-shadow: none;
        }

        .btn-outline:hover {
            border-color: var(--cyan);
            color: var(--cyan);
            background: rgba(6, 182, 212, 0.05);
            transform: translateY(-1.5px);
        }

        .btn-export-excel {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: #ffffff;
            border: 0;
            box-shadow: 0 2px 10px rgba(14, 165, 233, 0.35);
        }

        .btn-export-excel:hover {
            background: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.5);
            transform: translateY(-1.5px);
        }

        /* CLIENT-SIDE QUICK SEARCH INPUT (VIDEO 00:03 - 00:07) */
        .quick-search-box {
            position: relative;
            display: inline-flex;
            align-items: center;
            width: 230px;
            max-width: 100%;
        }

        .quick-search-box input {
            width: 100%;
            padding: 7px 12px 7px 32px;
            font-size: 12.5px;
            border-radius: 8px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--input-text);
            outline: none;
            transition: all 0.2s ease;
        }

        .quick-search-box input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
        }

        .quick-search-box svg {
            position: absolute;
            left: 10px;
            width: 14px;
            height: 14px;
            color: var(--text-muted);
            pointer-events: none;
        }

        /* INPUTS & SELECTS */
        input, select {
            font-family: inherit;
            font-size: 13px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--input-text);
            background: var(--input-bg);
            outline: none;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
            max-width: 100%;
        }

        input:focus, select:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.2);
        }

        /* MODERN DATA TABLE (VIDEO EXACT) */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--card-bg);
            scrollbar-width: thin;
        }

        .table-wrap::-webkit-scrollbar {
            height: 6px;
        }

        .table-wrap::-webkit-scrollbar-thumb {
            background: var(--border-hover);
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
            white-space: nowrap;
        }

        thead th {
            background: var(--table-head-bg);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            letter-spacing: 0.3px;
        }

        tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--table-border);
            color: var(--text-dark);
        }

        tbody tr {
            transition: background 0.15s ease;
        }

        tbody tr:hover {
            background: var(--table-row-hover);
        }

        tbody tr:last-child td { border-bottom: 0; }

        /* BADGES */
        .pill-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pill-in {
            background: var(--badge-in-bg);
            color: var(--badge-in-text);
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.25);
        }

        .pill-out {
            background: var(--badge-out-bg);
            color: var(--badge-out-text);
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.25);
        }

        .pill-in::before, .pill-out::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .pill-in::before { background: #10b981; }
        .pill-out::before { background: #ef4444; }

        .emp-id-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.08);
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(6, 182, 212, 0.2);
            display: inline-block;
            white-space: nowrap;
        }

        .emp-code-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.08);
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(56, 189, 248, 0.2);
            display: inline-block;
            white-space: nowrap;
        }

        .hours-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-dark);
            background: rgba(14, 165, 233, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(14, 165, 233, 0.2);
            display: inline-block;
        }

        /* TWO-ROW TABLE HEADER DESIGN (GUARANTEED NO OVERLAP) */
        .th-title-row th {
            padding: 12px 14px 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            border-bottom: 0;
            white-space: nowrap;
        }

        .th-filter-row th {
            padding: 4px 12px 12px;
            border-bottom: 1px solid var(--border);
            background: var(--table-head-bg);
        }

        .col-filter-box {
            position: relative;
            display: block !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .col-filter-box input {
            width: 100% !important;
            min-width: 90px !important;
            display: block !important;
            box-sizing: border-box !important;
            padding: 5px 24px 5px 8px;
            font-size: 11px;
            font-weight: 500;
            border-radius: 6px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--input-text);
            outline: none;
            transition: all 0.2s ease;
        }

        .col-filter-box input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.2);
        }

        .col-filter-box svg {
            position: absolute;
            right: 7px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            color: var(--text-muted);
            pointer-events: none;
        }

        /* REPORT FILTER BAR */
        .report-filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .report-filter-bar .filter-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
            min-width: 260px;
        }

        .report-filter-bar .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .date-range-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .date-range-wrap input[type="date"] {
            width: 135px;
            max-width: 100%;
        }

        .entries-preset-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
        }

        /* ALPHABET BAR */
        .alphabet-bar {
            display: flex;
            gap: 4px;
            align-items: center;
            padding: 10px 14px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 16px;
            overflow-x: auto;
            flex-wrap: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .alphabet-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-right: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .letter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 29px;
            height: 29px;
            padding: 0 6px;
            font-size: 11.5px;
            font-weight: 700;
            border-radius: 6px;
            color: var(--text-muted);
            background: var(--card-bg);
            border: 1px solid var(--border);
            text-decoration: none;
            flex-shrink: 0;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .letter-btn:hover {
            color: #06b6d4;
            border-color: #06b6d4;
            transform: translateY(-2px);
        }

        .letter-btn.active {
            background: #06b6d4;
            color: #ffffff;
            border-color: #06b6d4;
            box-shadow: 0 2px 10px rgba(6, 182, 212, 0.4);
        }

        /* AUTOCOMPLETE DROPDOWN */
        .autocomplete-container {
            position: relative;
            display: inline-block;
            width: auto;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            min-width: 310px;
            max-width: 380px;
            max-height: 360px;
            overflow-y: auto;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
            z-index: 1050;
            display: none;
            backdrop-filter: blur(8px);
        }

        .autocomplete-dropdown.open {
            display: block;
        }

        .autocomplete-header {
            padding: 8px 12px;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            background: var(--table-head-bg);
            border-bottom: 1px solid var(--border);
        }

        .autocomplete-item {
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s ease;
        }

        .autocomplete-item:last-child { border-bottom: 0; }

        .autocomplete-item:hover,
        .autocomplete-item.active {
            background: rgba(6, 182, 212, 0.12);
        }

        .autocomplete-main {
            font-weight: 700;
            font-size: 13px;
            color: var(--text-dark);
        }

        .autocomplete-sub {
            font-size: 11px;
            color: var(--text-muted);
        }

        .autocomplete-empty {
            padding: 14px 12px;
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
        }

        /* MOBILE BOTTOM NAV */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: calc(58px + env(safe-area-inset-bottom, 0));
            padding-bottom: env(safe-area-inset-bottom, 0);
            background: var(--topbar-bg);
            border-top: 1px solid var(--border);
            z-index: 950;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 10.5px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.18s ease;
        }

        .mobile-nav-item:hover, .mobile-nav-item.active {
            color: var(--cyan);
        }

        .mobile-nav-item svg {
            width: 20px;
            height: 20px;
        }

        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        /* ACTION BUTTONS (VIEW / EDIT / DELETE) */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 7px;
            border: 1px solid transparent;
            cursor: pointer;
            background: transparent;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 0;
            flex-shrink: 0;
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.08);
        }

        .btn-action:active {
            transform: translateY(0) scale(0.96);
        }

        .btn-action-view {
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.12);
            border-color: rgba(6, 182, 212, 0.25);
        }

        .btn-action-view:hover {
            background: #06b6d4;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        }

        .btn-action-edit {
            color: #0ea5e9;
            background: rgba(14, 165, 233, 0.12);
            border-color: rgba(14, 165, 233, 0.25);
        }

        .btn-action-edit:hover {
            background: #0ea5e9;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        .btn-action-delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.25);
        }

        .btn-action-delete:hover {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* FILTER TOOLBAR & SEGMENTED BUTTONS */
        .filter-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 16px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .segmented-toggle {
            display: inline-flex;
            background: var(--input-bg);
            padding: 3px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .segmented-btn {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            color: var(--text-muted);
            text-decoration: none;
            border: 0;
            background: none;
            cursor: pointer;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .segmented-btn:hover {
            color: var(--text-dark);
        }

        .segmented-btn.active {
            background: var(--cyan);
            color: #ffffff;
            box-shadow: 0 1px 6px rgba(6, 182, 212, 0.35);
        }

        /* PAGINATION */
        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-links {
            display: flex;
            gap: 4px;
            align-items: center;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--card-bg);
            color: var(--text-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.18s ease;
            min-width: 32px;
            text-align: center;
        }

        .page-btn:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            background: rgba(6, 182, 212, 0.1);
            transform: translateY(-1px);
        }

        .page-btn.active {
            background: var(--cyan);
            color: #ffffff;
            border-color: var(--cyan);
            box-shadow: 0 2px 10px rgba(6, 182, 212, 0.4);
        }

        .page-btn.disabled {
            color: var(--text-muted);
            background: var(--card-bg);
            border-color: var(--border);
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* MODAL STYLES */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(11, 19, 41, 0.75);
            z-index: 1100;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .modal-backdrop.open,
        .modal-backdrop[style*="display: flex"] {
            display: flex !important;
            opacity: 1;
        }

        .modal-dialog {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            width: 100%;
            max-width: 760px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            color: var(--text-dark);
        }

        .modal-grid {
            display: grid;
            gap: 12px;
            background: var(--input-bg);
            padding: 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .modal-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .modal-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .modal-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .form-grid-2 .span-2 {
            grid-column: span 2;
        }

        /* RESPONSIVE MEDIA QUERIES (ZERO OVERLAP ON MOBILE) */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                top: 0; bottom: 0;
                left: -260px;
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }
            .sidebar.open {
                left: 0;
            }
            .mobile-bottom-nav {
                display: flex;
            }
            .content {
                padding: 16px;
                padding-bottom: calc(76px + env(safe-area-inset-bottom, 0));
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 0 16px;
                height: 58px;
            }
            .topbar-title {
                font-size: 14px;
            }
            .auto-refresh-pill .refresh-label,
            .auto-refresh-pill .refresh-info {
                display: none;
            }
            .report-filter-bar .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            .date-range-wrap {
                width: 100%;
            }
            .date-range-wrap input[type="date"] {
                flex: 1;
            }
            .report-filter-bar select {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        @media (max-width: 640px) {
            .content {
                padding: 12px;
                padding-bottom: calc(76px + env(safe-area-inset-bottom, 0));
            }
            .user-info {
                display: none;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* PRINT STYLES */
        @media print {
            .sidebar, .topbar, .mobile-bottom-nav, .sidebar-backdrop,
            .report-tabs-container, .col-filter-box, .btn, .pagination-wrap,
            .menu-toggle, .auto-refresh-pill, .quick-search-box {
                display: none !important;
            }
            body, .app-shell, .main-wrapper, .content, .card {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                border: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
            }
            table {
                font-size: 11px !important;
            }
            thead th {
                background: #f1f5f9 !important;
                color: #000000 !important;
            }
            tbody td {
                color: #000000 !important;
            }
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="app-shell">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="brand-logo-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="brand-name">
                    <span class="brand-title">Workforce</span>
                    <span class="brand-badge">Analytics v2.4</span>
                </div>
            </a>

            <div class="nav-section">
                <div class="nav-header">ANALYTICS & REPORTS</div>

                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('reports.inout') }}" class="nav-item {{ request()->routeIs('reports.inout') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    <span>In-Out Report</span>
                </a>

                <a href="{{ route('reports.hours') }}" class="nav-item {{ request()->routeIs('reports.hours') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 14 10"></polyline>
                    </svg>
                    <span>Working Hours</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-header">MANAGEMENT</div>

                <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Employees</span>
                </a>

                <a href="{{ route('settings') }}" class="nav-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Settings</span>
                </a>
            </div>
        </aside>

        <!-- MOBILE SIDEBAR BACKDROP -->
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar(false)"></div>

        <!-- MAIN WRAPPER -->
        <div class="main-wrapper">
            <!-- TOPBAR (MATCHING VIDEO HEADER) -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="topbar-title">
                        <span>Workforce Attendance & Analytics</span>
                        <span class="version-pill">v2.4</span>
                    </div>
                </div>

                <div class="topbar-right">
                    <!-- AUTO REFRESH TOGGLE (EXACT MATCH TO VIDEO 00:00 & 00:07) -->
                    <div class="auto-refresh-pill" id="autoRefreshContainer" title="Toggle 30s auto-refresh">
                        <span class="refresh-label">Auto-refresh in 30s</span>
                        <span class="refresh-info">ⓘ</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="autoRefreshCheckbox" checked onchange="toggleAutoRefresh(this.checked)">
                            <span class="slider"></span>
                        </label>
                        <span class="countdown-badge" id="autoRefreshTimer">30s</span>
                    </div>

                    <!-- THEME TOGGLE (DARK / LIGHT) -->
                    <button class="icon-btn" onclick="toggleTheme()" title="Switch Light/Dark Mode" id="themeToggleBtn">
                        <svg class="sun-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>

                    <!-- NOTIFICATIONS -->
                    <button class="icon-btn" title="Notifications">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <span class="badge-dot"></span>
                    </button>

                    <!-- USER PROFILE PILL -->
                    <a href="{{ route('login') }}" class="user-pill">
                        <div class="user-avatar">
                            <span>A</span>
                            <span class="user-avatar-dot"></span>
                        </div>
                        <div class="user-info">
                            <span class="user-name">User Profile</span>
                            <span class="user-role">Administrator</span>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--text-muted)">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('reports.inout') }}" class="mobile-nav-item {{ request()->routeIs('reports.inout') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
            <span>In-Out</span>
        </a>
        <a href="{{ route('reports.hours') }}" class="mobile-nav-item {{ request()->routeIs('reports.hours') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 10"></polyline></svg>
            <span>Working Hours</span>
        </a>
        <a href="{{ route('employees.index') }}" class="mobile-nav-item {{ request()->routeIs('employees*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
            <span>Employees</span>
        </a>
    </nav>

    <!-- CORE SYSTEM SCRIPTS -->
    <script>
    // THEME CONTROLLER
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', nextTheme);
        localStorage.setItem('workforce_theme', nextTheme);
    }

    // AUTO REFRESH 30S TIMER (AS SEEN IN VIDEO 00:00 & 00:07)
    let autoRefreshSec = 30;
    let autoRefreshActive = true;
    let autoRefreshInterval = null;

    function startAutoRefreshTimer() {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        autoRefreshInterval = setInterval(() => {
            if (!autoRefreshActive) return;
            autoRefreshSec--;
            const badge = document.getElementById('autoRefreshTimer');
            if (badge) badge.innerText = `${autoRefreshSec}s`;

            if (autoRefreshSec <= 0) {
                autoRefreshSec = 30;
                window.location.reload();
            }
        }, 1000);
    }

    function toggleAutoRefresh(enable) {
        autoRefreshActive = enable;
        const badge = document.getElementById('autoRefreshTimer');
        if (!enable) {
            if (badge) badge.innerText = 'Off';
        } else {
            autoRefreshSec = 30;
            if (badge) badge.innerText = '30s';
        }
    }

    // INSTANT CLIENT-SIDE QUICK SEARCH (VIDEO 00:03 - 00:07)
    function setupQuickReportSearch(inputSelector, tableSelector) {
        const input = document.querySelector(inputSelector);
        const table = document.querySelector(tableSelector);
        if (!input || !table) return;

        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                // If it's an empty-state row, ignore
                if (row.cells.length <= 1) return;
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // CLIENT-SIDE EXCEL / CSV EXPORT ENGINE
    function exportTableToExcel(tableSelector, filename) {
        const table = document.querySelector(tableSelector);
        if (!table) return;

        let csvContent = "\uFEFF"; // UTF-8 BOM
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const cols = row.querySelectorAll('th, td');
            const rowData = [];
            cols.forEach(col => {
                // Remove internal filter inputs or icons from header text
                let text = col.cloneNode(true);
                const filterInputs = text.querySelectorAll('.col-filter-box, input, svg');
                filterInputs.forEach(el => el.remove());
                let cleanText = text.innerText.trim().replace(/"/g, '""');
                rowData.push(`"${cleanText}"`);
            });
            if (rowData.length > 0) {
                csvContent += rowData.join(',') + "\r\n";
            }
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', (filename || 'attendance_report') + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // SIDEBAR TOGGLE
    function toggleSidebar(forceState) {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar) return;
        const shouldOpen = forceState !== undefined ? forceState : !sidebar.classList.contains('open');
        sidebar.classList.toggle('open', shouldOpen);
        if (backdrop) {
            backdrop.classList.toggle('show', shouldOpen);
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleSidebar(false);
        }
    });

    // DICTIONARY AUTOCOMPLETE SUGGESTIONS
    function setupAutocomplete(inputSelector) {
        document.querySelectorAll(inputSelector).forEach(input => {
            if (input.dataset.autocompleteInit) return;
            input.dataset.autocompleteInit = 'true';

            let container = input.parentElement;
            if (!container.classList.contains('autocomplete-container')) {
                container.classList.add('autocomplete-container');
            }

            const dropdown = document.createElement('div');
            dropdown.className = 'autocomplete-dropdown';
            container.appendChild(dropdown);

            let debounceTimer = null;
            let activeIndex = -1;
            let currentItems = [];

            function renderSuggestions(items, q) {
                currentItems = items;
                activeIndex = -1;
                dropdown.innerHTML = '';

                if (!items || items.length === 0) {
                    dropdown.innerHTML = `<div class="autocomplete-empty">No matching employee found for "<strong>${escapeHtml(q)}</strong>"</div>`;
                    dropdown.classList.add('open');
                    return;
                }

                const header = document.createElement('div');
                header.className = 'autocomplete-header';
                header.innerText = `DICTIONARY SUGGESTIONS (${items.length})`;
                dropdown.appendChild(header);

                items.forEach((item, index) => {
                    const el = document.createElement('div');
                    el.className = 'autocomplete-item';
                    el.innerHTML = `
                        <div>
                            <div class="autocomplete-main">${escapeHtml(item.emp_name)}</div>
                            <div class="autocomplete-sub">${escapeHtml(item.designation || 'Operator')} &bull; ID: <strong>${escapeHtml(item.id)}</strong></div>
                        </div>
                        <span class="emp-id-badge">#${escapeHtml(item.id)}</span>
                    `;
                    el.addEventListener('click', () => {
                        selectSuggestion(item);
                    });
                    dropdown.appendChild(el);
                });

                dropdown.classList.add('open');
            }

            function selectSuggestion(item) {
                input.value = item.id;
                dropdown.classList.remove('open');
                const form = input.form || input.closest('form');
                if (form) form.submit();
            }

            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]);
            }

            input.addEventListener('input', () => {
                const q = input.value.trim();
                clearTimeout(debounceTimer);

                if (q.length < 1) {
                    dropdown.classList.remove('open');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('api.suggestions') }}?q=${encodeURIComponent(q)}`)
                        .then(res => res.json())
                        .then(data => {
                            renderSuggestions(data, q);
                        })
                        .catch(err => console.error('Suggestion error:', err));
                }, 160);
            });

            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        startAutoRefreshTimer();
        setupAutocomplete('input[name="search"], input.search-suggest');
    });
    </script>

    @stack('scripts')
</body>
</html>
