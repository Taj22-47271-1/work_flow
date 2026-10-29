@extends('attendance.layout')
@section('title', 'Attendance & Working Hours Report (Report 2) · Workforce Analytics')

@section('content')
<!-- UNIFIED DUAL REPORT TABS (EXACT TO VIDEO 00:00 - 00:06) -->
<div class="report-tabs-container">
    <div class="report-tabs-bar">
        <a href="{{ route('reports.inout', request()->query()) }}" class="report-tab-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="17 1 21 5 17 9"></polyline>
                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                <polyline points="7 23 3 19 7 15"></polyline>
                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
            </svg>
            <span>In-Out Report (Report 1)</span>
        </a>
        <a href="{{ route('reports.hours', request()->query()) }}" class="report-tab-btn active">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 14 10"></polyline>
            </svg>
            <span>Attendance & Working Hours Report (Report 2)</span>
        </a>
        <div class="report-tab-add" title="Switch Report View">+</div>
    </div>
    <div class="report-tab-hint">(Toggle tabs to switch between In-Out log events and Daily Aggregated Working Hours)</div>
</div>

<!-- TOP FILTER BAR -->
<div class="card" style="padding:16px 20px; margin-bottom:20px;">
    <form method="GET" action="{{ route('reports.hours') }}" id="filterForm" class="report-filter-bar">
        <div class="filter-controls">
            <!-- DATE RANGE -->
            <div class="date-range-wrap">
                <input type="date" name="from" value="{{ $from }}" style="font-weight:600;" required>
                <span style="color:var(--text-muted); font-weight:700;">—</span>
                <input type="date" name="to" value="{{ $to }}" style="font-weight:600;" required>
            </div>

            <!-- EMPLOYEE SELECT -->
            <select name="employee" style="font-weight:600; min-width:170px; max-width:220px; font-size:12.5px;">
                <option value="all">-- All Employees --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->id }} - {{ $emp->emp_name }}
                    </option>
                @endforeach
            </select>

            <!-- GENDER FILTER -->
            <select name="gender" style="font-weight:600; font-size:12.5px;">
                <option value="">All Genders</option>
                <option value="M" {{ request('gender') === 'M' ? 'selected' : '' }}>👨 Male</option>
                <option value="F" {{ request('gender') === 'F' ? 'selected' : '' }}>👩 Female</option>
            </select>

            <!-- SORTING -->
            <select name="sort" style="font-weight:600; font-size:12.5px;">
                <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>📅 Default Date</option>
                <option value="id_asc" {{ request('sort') === 'id_asc' ? 'selected' : '' }}>🔢 ID: Low to High</option>
                <option value="id_desc" {{ request('sort') === 'id_desc' ? 'selected' : '' }}>🔢 ID: High to Low</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>🔤 Name: A to Z</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>🔤 Name: Z to A</option>
            </select>

            <!-- SEARCH SUBMIT BUTTON -->
            <button type="submit" class="btn" style="padding:8px 18px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Search
            </button>
        </div>

        <!-- EXPORT ACTION BUTTONS (VIDEO 00:03 - 00:09) -->
        <div class="action-buttons">
            <button type="button" class="btn btn-export-excel" onclick="exportTableToExcel('#hoursTable', 'Working_Hours_Report_{{ $from }}_to_{{ $to }}')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Excel
            </button>

            <button type="button" class="btn btn-outline" onclick="window.print()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9V2h12v7"></path>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Export PDF
            </button>
        </div>
    </form>
</div>

<!-- MAIN TABLE CARD -->
<div class="card" style="padding:22px;">
    <!-- CARD HEADER WITH CLIENT-SIDE QUICK SEARCH (VIDEO 00:03 - 00:07) -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:14px;">
        <div>
            <h2 style="font-size:18px; font-weight:800; margin:0 0 4px; color:var(--text-dark); display:flex; align-items:center; gap:8px;">
                <span>Attendance & Working Hours Report</span>
                <span class="emp-id-badge" style="font-size:11px;">Report 2</span>
            </h2>
            <div style="font-size:12px; color:var(--text-muted);">
                Range: <strong>{{ \Carbon\Carbon::parse($from)->format('d-m-Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($to)->format('d-m-Y') }}</strong>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <!-- CLIENT-SIDE QUICK FILTER (REAL-TIME INSTANT FILTER AS SEEN IN VIDEO) -->
            <div class="quick-search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="quickReportSearch" placeholder="Quick search within report..." autocomplete="off">
            </div>

            <!-- SERVER-SIDE GLOBAL SEARCH -->
            <div class="quick-search-box" style="width:190px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" value="{{ request('search') }}" form="filterForm" placeholder="Global search..." onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
            </div>
        </div>
    </div>

    <!-- SHOW ENTRIES & QUICK PRESETS -->
    <div class="entries-preset-bar">
        <div style="display:flex; align-items:center; gap:6px; font-size:12.5px; color:var(--text-muted);">
            Show
            <select name="per_page" form="filterForm" onchange="document.getElementById('filterForm').submit()" style="padding:4px 8px; font-size:12px;">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            entries
        </div>

        <!-- QUICK PRESET SHORTCUTS -->
        <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
            <a class="btn btn-outline" style="padding:5px 10px; font-size:11.5px;" href="{{ route('reports.hours', ['from' => '2026-01-01', 'to' => '2026-01-30', 'employee' => '198276']) }}">📌 Jan 2026 (Mr. ABC)</a>
            <a class="btn btn-outline" style="padding:5px 10px; font-size:11.5px;" href="{{ route('reports.hours', ['from' => '2026-03-01', 'to' => '2026-03-31', 'employee' => 'all']) }}">👥 100 Employees (March 2026)</a>
        </div>
    </div>

    <!-- DATA TABLE (WITH SORT INDICATORS ⇅ AND COLUMN SEARCH FILTERS) -->
    <div class="table-wrap">
        <table id="hoursTable">
            <thead>
                <tr class="th-title-row">
                    <th style="width:55px; min-width:55px; text-align:center;">SL</th>
                    <th style="min-width:130px;">Employee ID ⇅</th>
                    <th style="min-width:180px;">Employee Name ⇅</th>
                    <th style="min-width:140px;">Designation ⇅</th>
                    <th style="min-width:160px;">Login Time ⇅</th>
                    <th style="min-width:160px;">Logout Time ⇅</th>
                    <th style="min-width:130px;">Working Hours ⇅</th>
                </tr>
                <tr class="th-filter-row">
                    <th style="text-align:center;"></th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[employee_id]" form="filterForm" value="{{ request('filter.employee_id') }}" placeholder="Filter ID" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[employee_name]" form="filterForm" value="{{ request('filter.employee_name') }}" placeholder="Filter Name" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[designation]" form="filterForm" value="{{ request('filter.designation') }}" placeholder="Filter Designation" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[login_time]" form="filterForm" value="{{ request('filter.login_time') }}" placeholder="Filter Login" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[logout_time]" form="filterForm" value="{{ request('filter.logout_time') }}" placeholder="Filter Logout" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                    <th>
                        <div class="col-filter-box">
                            <input name="filter[working_hours]" form="filterForm" value="{{ request('filter.working_hours') }}" placeholder="Filter Hours" onkeydown="if(event.key==='Enter')document.getElementById('filterForm').submit()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                @php
                    $hrs = (float)($row->working_hours ?? 0);
                @endphp
                <tr>
                    <td style="color:var(--text-muted); font-weight:600; text-align:center;">
                        {{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <span class="emp-id-badge">#{{ $row->employee_id }}</span>
                    </td>
                    <td style="font-weight:700; color:var(--text-dark);">
                        {{ $row->employee_name }}
                    </td>
                    <td style="color:var(--text-muted);">
                        {{ $row->designation ?: '-' }}
                    </td>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:12.5px; color:var(--text-dark);">
                        {{ $row->login_time ?: 'No Login' }}
                    </td>
                    <td style="font-family:'JetBrains Mono',monospace; font-size:12.5px; color:var(--text-dark);">
                        {{ $row->logout_time ?: 'No Logout' }}
                    </td>
                    <td>
                        <span class="hours-badge">
                            {{ number_format($hrs, 2) }} hrs
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                        <div style="font-size:14px; font-weight:600; margin-bottom:4px;">No attendance records found</div>
                        <div style="font-size:12px;">Try adjusting the date range or employee filter above.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION FOOTER -->
    {{ $rows->links('attendance.pagination') }}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Enable instant real-time client-side filter
    setupQuickReportSearch('#quickReportSearch', '#hoursTable');
});
</script>
@endpush
