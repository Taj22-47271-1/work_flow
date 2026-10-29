@extends('attendance.layout')
@section('title', 'Employee Attendance Reports & Analytics · Workforce Analytics')

@section('content')
<!-- DASHBOARD HEADER BAR (MATCHING VIDEO 00:00) -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:16px;">
    <div>
        <h1 style="font-size:22px; font-weight:800; margin:0 0 4px; color:var(--text-dark); letter-spacing:-0.4px;">
            Employee Attendance Reports & Analytics
        </h1>
        <p style="margin:0; font-size:12.5px; color:var(--text-muted);">
            Total daily working hours trend (Range: <strong style="color:var(--text-dark);">{{ \Carbon\Carbon::parse($from)->format('d M Y') }}</strong> to <strong style="color:var(--text-dark);">{{ \Carbon\Carbon::parse($to)->format('d M Y') }}</strong> &bull; {{ $days }} days)
        </p>
    </div>

    <!-- DATE RANGE FILTER -->
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <select id="dateRangeSelect" onchange="applyDateRange(this.value)" style="padding:8px 12px; font-weight:700; font-size:12.5px; cursor:pointer;">
            <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days (30 bars) 📅</option>
            <option value="10" {{ $days == 10 ? 'selected' : '' }}>Last 10 days (10 bars) 📅</option>
            <option value="120" {{ $days == 120 ? 'selected' : '' }}>Last 120 days (120 bars) 📅</option>
            <option value="jan">January 2026 (Mr. ABC)</option>
            <option value="march">March 2026 (100 Employees)</option>
        </select>

        <form method="GET" action="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:6px; flex-wrap:wrap;">
            <input type="date" name="from" value="{{ $from }}" style="width:130px; font-size:12px; padding:7px 10px;" required>
            <span style="color:var(--text-muted); font-weight:700;">—</span>
            <input type="date" name="to" value="{{ $to }}" style="width:130px; font-size:12px; padding:7px 10px;" required>
            <button type="submit" class="btn" style="padding:7px 14px; font-size:12px;">Apply</button>
        </form>
    </div>
</div>

<!-- DAILY WORKING HOURS CHART CARD (EXACT VIDEO 00:00 - 00:02 & 00:09) -->
<div class="card" style="margin-bottom:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
        <div style="font-weight:800; font-size:16px; color:var(--text-dark); display:flex; align-items:center; gap:8px;">
            <span>Total Working Hours Per Day (Last 30 Days)</span>
            <span class="emp-id-badge" style="font-size:11px;">Interactive Chart</span>
        </div>
        <div style="font-size:12px; color:var(--text-muted); display:flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            Hover bars to view exact aggregate hours
        </div>
    </div>

    <div style="height:320px; width:100%; position:relative;">
        <canvas id="hoursChart"></canvas>
    </div>
</div>

<!-- 4 STAT CARDS ROW AT BOTTOM -->
<div class="stats-grid">
    <!-- CARD 1: Total Employees -->
    <div class="card" style="margin-bottom:0; display:flex; align-items:center; gap:16px; padding:18px 20px;">
        <div style="width:48px; height:48px; border-radius:12px; background:rgba(6, 182, 212, 0.12); color:#06b6d4; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(6, 182, 212, 0.25);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div>
            <div style="font-size:12px; color:var(--text-muted); font-weight:600; margin-bottom:2px;">Total Employees</div>
            <div style="font-size:26px; font-weight:800; color:var(--text-dark); line-height:1;">{{ $totalEmployees ?? 101 }}</div>
        </div>
    </div>

    <!-- CARD 2: Present Today -->
    <div class="card" style="margin-bottom:0; display:flex; align-items:center; gap:16px; padding:18px 20px;">
        <div style="width:48px; height:48px; border-radius:12px; background:rgba(16, 185, 129, 0.12); color:#10b981; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(16, 185, 129, 0.25);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div>
            <div style="font-size:12px; color:var(--text-muted); font-weight:600; margin-bottom:2px;">Present Today</div>
            <div style="font-size:26px; font-weight:800; color:var(--text-dark); line-height:1;">{{ $presentToday ?? 84 }}</div>
        </div>
    </div>

    <!-- CARD 3: Absent Today -->
    <div class="card" style="margin-bottom:0; display:flex; align-items:center; gap:16px; padding:18px 20px;">
        <div style="width:48px; height:48px; border-radius:12px; background:rgba(239, 68, 68, 0.12); color:#ef4444; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(239, 68, 68, 0.25);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div>
            <div style="font-size:12px; color:var(--text-muted); font-weight:600; margin-bottom:2px;">Absent Today</div>
            <div style="font-size:26px; font-weight:800; color:var(--text-dark); line-height:1;">{{ $absentToday ?? 17 }}</div>
        </div>
    </div>

    <!-- CARD 4: Avg. Working Hours -->
    <div class="card" style="margin-bottom:0; display:flex; align-items:center; gap:16px; padding:18px 20px;">
        <div style="width:48px; height:48px; border-radius:12px; background:rgba(14, 165, 233, 0.12); color:#0ea5e9; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid rgba(14, 165, 233, 0.25);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
            </svg>
        </div>
        <div>
            <div style="font-size:12px; color:var(--text-muted); font-weight:600; margin-bottom:2px;">Avg. Working Hours</div>
            <div style="font-size:26px; font-weight:800; color:var(--text-dark); line-height:1;">
                {{ $avgWorkingHours ?? 7.8 }} <span style="font-size:14px; font-weight:600; color:var(--text-muted);">hrs</span>
            </div>
        </div>
    </div>
</div>

<form id="dashboardRangeForm" method="GET" action="{{ route('dashboard') }}" style="display:none;">
    <input type="hidden" name="from" id="dashFrom">
    <input type="hidden" name="to" id="dashTo">
</form>
@endsection

@push('scripts')
<script>
const rawLabels = @json(array_keys($series));
const rawValues = @json(array_values($series));

// Format labels nicely
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const chartLabels = rawLabels.map(d => {
    const parts = d.split('-');
    if (parts.length === 3) {
        const m = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);
        return `${months[m] || parts[1]} ${day}`;
    }
    return d;
});

const ctx = document.getElementById('hoursChart').getContext('2d');

// Create vibrant cyan to teal gradient
const cyanGradient = ctx.createLinearGradient(0, 0, 0, 300);
cyanGradient.addColorStop(0, '#06b6d4');
cyanGradient.addColorStop(1, '#0284c7');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Total Working Hours',
            data: rawValues,
            backgroundColor: cyanGradient,
            hoverBackgroundColor: '#22d3ee',
            borderRadius: 6,
            borderSkipped: false,
            barPercentage: 0.65,
            categoryPercentage: 0.85
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0b1329',
                titleColor: '#06b6d4',
                bodyColor: '#ffffff',
                borderColor: '#1e293b',
                borderWidth: 1,
                padding: 12,
                displayColors: false,
                callbacks: {
                    title: function(items) {
                        const idx = items[0].dataIndex;
                        return rawLabels[idx] || items[0].label;
                    },
                    label: function(ctx) {
                        return `${ctx.parsed.y} hrs`;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" },
                    maxRotation: 45
                }
            },
            y: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" },
                    callback: function(val) { return val + 'h'; }
                }
            }
        }
    }
});

function applyDateRange(val) {
    const f = document.getElementById('dashFrom');
    const t = document.getElementById('dashTo');
    const form = document.getElementById('dashboardRangeForm');

    if (val === 'jan') {
        f.value = '2026-01-01';
        t.value = '2026-01-30';
        form.submit();
    } else if (val === 'march') {
        f.value = '2026-03-01';
        t.value = '2026-03-31';
        form.submit();
    } else {
        const days = parseInt(val, 10);
        const end = new Date('2026-09-17');
        const start = new Date(end);
        start.setDate(end.getDate() - (days - 1));
        f.value = start.toISOString().split('T')[0];
        t.value = end.toISOString().split('T')[0];
        form.submit();
    }
}
</script>
@endpush
