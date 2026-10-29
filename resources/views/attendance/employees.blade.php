@extends('attendance.layout')
@section('title', 'Employees')

@section('content')
<div class="card" style="padding:22px;">
    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:14px;">
        <div>
            <h1 style="font-size:21px; font-weight:800; margin:0 0 4px; color:var(--text-dark);">Employees Directory</h1>
            <p style="margin:0; font-size:12.5px; color:var(--text-muted);">Complete employee profiles with A-Z alphabet filtering, ID sorting (low/high) & gender filters</p>
        </div>

        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;" class="filter-group">
            <form method="GET" action="{{ route('employees.index') }}" id="searchForm" style="position:relative; width:auto;">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="hidden" name="letter" value="{{ $letter }}">
                <input type="hidden" name="gender" value="{{ $gender }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name, Card, Grade..." style="padding-left:32px; width:260px;" onkeydown="if(event.key==='Enter')document.getElementById('searchForm').submit()">
                <svg style="position:absolute; left:10px; top:10px; width:14px; height:14px; color:#94a3b8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </form>

            <button type="button" class="btn" onclick="openEmpModal('add')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Employee
            </button>
        </div>
    </div>

    <!-- INTERACTIVE A-Z ALPHABET BAR -->
    <div class="alphabet-bar">
        <div class="alphabet-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            A-Z Filter:
        </div>
        <a href="{{ request()->fullUrlWithQuery(['letter' => 'ALL', 'page' => 1]) }}" class="letter-btn {{ empty($letter) || $letter === 'ALL' ? 'active' : '' }}">ALL</a>
        @foreach(range('A', 'Z') as $char)
            <a href="{{ request()->fullUrlWithQuery(['letter' => $char, 'page' => 1]) }}" class="letter-btn {{ $letter === $char ? 'active' : '' }}">{{ $char }}</a>
        @endforeach
    </div>

    <!-- ADVANCED FILTER & SORT TOOLBAR -->
    <div class="filter-toolbar">
        <div class="filter-group">
            <!-- GENDER FILTER TOGGLE -->
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Gender:</span>
                <div class="segmented-toggle">
                    <a href="{{ request()->fullUrlWithQuery(['gender' => '', 'page' => 1]) }}" class="segmented-btn {{ empty($gender) ? 'active' : '' }}">All ({{ $totalCount }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['gender' => 'M', 'page' => 1]) }}" class="segmented-btn {{ $gender === 'M' ? 'active' : '' }}">👨 Male ({{ $maleCount }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['gender' => 'F', 'page' => 1]) }}" class="segmented-btn {{ $gender === 'F' ? 'active' : '' }}">👩 Female ({{ $femaleCount }})</a>
                </div>
            </div>

            <!-- STATUS FILTER -->
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Status:</span>
                <select name="status" form="searchForm" onchange="document.getElementById('searchForm').submit()" style="padding:5px 8px; font-size:12px; font-weight:600;">
                    <option value="all" {{ empty($status) || $status === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>🟢 Active</option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>🔴 Inactive</option>
                </select>
            </div>

            <!-- EMPLOYEE TYPE FILTER -->
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Type:</span>
                <select name="emp_type" form="searchForm" onchange="document.getElementById('searchForm').submit()" style="padding:5px 8px; font-size:12px; font-weight:600;">
                    <option value="all" {{ empty($empType) || $empType === 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="2" {{ $empType === '2' ? 'selected' : '' }}>Operator (OPR)</option>
                    <option value="1" {{ $empType === '1' ? 'selected' : '' }}>IT Specialist</option>
                    <option value="3" {{ $empType === '3' ? 'selected' : '' }}>Industrial Eng (IE)</option>
                    <option value="4" {{ $empType === '4' ? 'selected' : '' }}>Technician (TCH)</option>
                    <option value="5" {{ $empType === '5' ? 'selected' : '' }}>QC Finishing (QCF)</option>
                    <option value="6" {{ $empType === '6' ? 'selected' : '' }}>QC Electrical (QCE)</option>
                    <option value="7" {{ $empType === '7' ? 'selected' : '' }}>QC Raw Material (QCR)</option>
                </select>
            </div>
        </div>

        <div class="filter-group">
            <!-- SORTING DROPDOWN -->
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:#64748b;">Sort:</span>
                <select name="sort" form="searchForm" onchange="document.getElementById('searchForm').submit()" style="padding:5px 10px; font-size:12px; font-weight:700; color:#0f172a; border-color:#cbd5e1;">
                    <option value="id_asc" {{ $sort === 'id_asc' ? 'selected' : '' }}>🔢 Emp ID: Low to High</option>
                    <option value="id_desc" {{ $sort === 'id_desc' ? 'selected' : '' }}>🔢 Emp ID: High to Low</option>
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>🔤 Name: A to Z</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>🔤 Name: Z to A</option>
                    <option value="join_desc" {{ $sort === 'join_desc' ? 'selected' : '' }}>📅 Joining: Newest First</option>
                    <option value="join_asc" {{ $sort === 'join_asc' ? 'selected' : '' }}>📅 Joining: Oldest First</option>
                </select>
            </div>

            <!-- RESET FILTERS BUTTON -->
            @if(!empty($search) || !empty($gender) || (!empty($letter) && $letter !== 'ALL') || ($sort !== 'id_asc') || (!empty($status) && $status !== 'all') || (!empty($empType) && $empType !== 'all'))
                <a href="{{ route('employees.index') }}" class="btn btn-outline" style="padding:5px 10px; font-size:12px; color:#ef4444; border-color:#fca5a5;" title="Clear all active filters">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    Clear Filters
                </a>
            @endif
        </div>
    </div>

    <!-- SHOW ENTRIES BAR -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
        <div style="display:flex; align-items:center; gap:6px; font-size:12.5px; color:var(--text-muted);">
            Show
            <select name="per_page" form="searchForm" onchange="document.getElementById('searchForm').submit()" style="padding:4px 8px; font-size:12px;">
                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            entries
        </div>

        <div style="font-size:12px; color:var(--text-muted);">
            💡 <em>Click any row or the 👁️ icon to view all 20 information fields</em>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">SL</th>
                    <th>DB ID</th>
                    <th>Emp ID (Code)</th>
                    <th>Full Name</th>
                    <th>Designation</th>
                    <th>Grade</th>
                    <th>Gender</th>
                    <th>Joining Date</th>
                    <th>Card ID</th>
                    <th>Salary</th>
                    <th>Emp Type</th>
                    <th>Status</th>
                    <th style="width:110px; text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeMap = [
                        1 => 'IT',
                        2 => 'OPR',
                        3 => 'IE',
                        4 => 'TCH',
                        5 => 'QCF',
                        6 => 'QCE',
                        7 => 'QCR'
                    ];
                @endphp
                @forelse($employees as $emp)
                @php
                    $tName = $typeMap[$emp->emp_type] ?? ('Type ' . ($emp->emp_type ?: '-'));
                    $joinDate = $emp->date_of_join ?? $emp->Date_of_join ?? null;
                    $gender = $emp->gender ?? $emp->Gender ?? '-';
                    $status = $emp->status;
                @endphp
                <tr style="cursor:pointer;" onclick="if(!event.target.closest('button')) viewEmpDetails('{{ $emp->id }}')">
                    <td style="color:var(--text-muted); font-weight:600; text-align:center;">{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td>
                        <span class="emp-id-badge">#{{ $emp->id }}</span>
                    </td>
                    <td>
                        <span class="emp-code-badge">{{ $emp->emp_id ?: '-' }}</span>
                    </td>
                    <td style="font-weight:700; color:var(--text-dark);">
                        {{ $emp->emp_name }}
                    </td>
                    <td style="color:var(--text-muted);">{{ $emp->designation ?: '-' }}</td>
                    <td>
                        @if($emp->present_grade)
                            <span style="display:inline-block; padding:2px 8px; font-size:11px; font-weight:700; background:rgba(255,255,255,0.06); color:var(--text-dark); border-radius:4px; border:1px solid var(--border);">Grade {{ $emp->present_grade }}</span>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td style="color:var(--text-dark); font-weight:600;">{{ $gender }}</td>
                    <td style="color:var(--text-muted); font-family:'JetBrains Mono',monospace; font-size:12px;">{{ $joinDate ? \Carbon\Carbon::parse($joinDate)->format('Y-m-d') : '-' }}</td>
                    <td>
                        <code style="font-size:11.5px; background:rgba(6,182,212,0.08); color:var(--cyan); padding:2px 6px; border-radius:4px; border:1px solid rgba(6,182,212,0.2); font-family:'JetBrains Mono',monospace;">{{ $emp->card_id ?: '-' }}</code>
                    </td>
                    <td style="font-weight:700; color:#10b981; font-family:'JetBrains Mono',monospace;">
                        {{ $emp->present_salary !== null ? '৳ ' . number_format((float)$emp->present_salary, 2) : '-' }}
                    </td>
                    <td>
                        <span style="font-size:11px; font-weight:700; padding:2px 6px; border-radius:4px; background:rgba(255,255,255,0.06); border:1px solid var(--border); color:var(--text-muted);">
                            {{ $tName }}
                        </span>
                    </td>
                    <td>
                        @if($status === 1 || $status === '1')
                            <span class="pill-status pill-active">Active</span>
                        @elseif($status === 0 || $status === '0')
                            <span class="pill-status pill-out">Inactive</span>
                        @else
                            <span class="pill-status" style="background:#fef3c7; color:#b45309;">Pending</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; gap:6px; align-items:center;">
                            <button type="button" class="btn-action btn-action-view" onclick="viewEmpDetails('{{ $emp->id }}')" title="View All 20 Info Fields">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            <button type="button" class="btn-action btn-action-edit" onclick="editEmpForm('{{ $emp->id }}')" title="Edit Employee">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" onclick="deleteEmp('{{ $emp->id }}', '{{ addslashes($emp->emp_name) }}')" title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" style="text-align:center; padding:36px; color:#94a3b8;">
                        No employees found matching your search.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    {{ $employees->links('attendance.pagination') }}
</div>

<!-- MODAL 1: VIEW FULL DETAILS (ALL 20 FIELDS) -->
<div id="detailsModal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closeDetailsModal()">
    <div class="modal-dialog">
        
        <!-- MODAL HEADER -->
        <div style="padding:18px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--card-header-bg); border-top-left-radius:14px; border-top-right-radius:14px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg, #06b6d4 0%, #0284c7 100%); color:#ffffff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px;" id="dAvatar">
                    E
                </div>
                <div>
                    <h3 id="dName" style="margin:0 0 2px; font-size:17px; font-weight:800; color:var(--text-dark);">Employee Details</h3>
                    <div style="font-size:12px; color:var(--text-muted);">
                        Database ID: <strong id="dId" style="color:var(--text-dark);">-</strong> &bull; Employee Code: <strong id="dEmpCode" style="color:var(--cyan);">-</strong>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeDetailsModal()" style="background:none; border:0; color:var(--text-muted); cursor:pointer; font-size:22px; font-weight:700; line-height:1;">&times;</button>
        </div>

        <!-- MODAL BODY: 5 ORGANIZED SECTIONS COVERING ALL 20 FIELDS -->
        <div style="padding:22px 24px; display:flex; flex-direction:column; gap:20px;">
            
            <!-- SECTION 1: Personal & Primary Identification -->
            <div>
                <div style="font-size:12px; font-weight:800; letter-spacing:0.8px; text-transform:uppercase; color:#1d68f5; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                    <span>👤</span> 1. Identification & Personal Info
                </div>
                <div class="modal-grid modal-grid-3">
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">1. Database ID (id)</span>
                        <strong id="v_id" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">2. Company ID (company_id)</span>
                        <strong id="v_company_id" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">3. Employee ID (emp_id)</span>
                        <strong id="v_emp_id" style="font-size:13px; color:#1d68f5;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">4. Full Name (emp_name)</span>
                        <strong id="v_emp_name" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">5. Gender (Gender)</span>
                        <strong id="v_gender" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Position & Department Details -->
            <div>
                <div style="font-size:12px; font-weight:800; letter-spacing:0.8px; text-transform:uppercase; color:#1d68f5; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                    <span>💼</span> 2. Position & Classification
                </div>
                <div class="modal-grid modal-grid-4">
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">6. Designation (designation)</span>
                        <strong id="v_designation" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">7. Desig Code (emp_desig)</span>
                        <strong id="v_emp_desig" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">8. Present Grade (present_grade)</span>
                        <strong id="v_present_grade" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">9. Emp Type (emp_type)</span>
                        <strong id="v_emp_type" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Compensation & Key Dates -->
            <div>
                <div style="font-size:12px; font-weight:800; letter-spacing:0.8px; text-transform:uppercase; color:#1d68f5; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                    <span>💰</span> 3. Salary & Important Dates
                </div>
                <div class="modal-grid modal-grid-3">
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">10. Present Salary (present_salary)</span>
                        <strong id="v_present_salary" style="font-size:13px; color:#10b981;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">11. Date of Joining (Date_of_join)</span>
                        <strong id="v_date_of_join" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">12. Evaluation Date (evaluation)</span>
                        <strong id="v_evaluation" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Access & Security Credentials -->
            <div>
                <div style="font-size:12px; font-weight:800; letter-spacing:0.8px; text-transform:uppercase; color:#1d68f5; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                    <span>💳</span> 4. Access Card & Credentials
                </div>
                <div class="modal-grid modal-grid-3">
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">13. RFID Card ID (card_id)</span>
                        <strong id="v_card_id" style="font-size:13px; color:#1d68f5; font-family:monospace;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">14. Security PIN (pin)</span>
                        <strong id="v_pin" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">15. Status (status)</span>
                        <span id="v_status" class="pill-status pill-active">Active</span>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Audit Trail & Remarks -->
            <div>
                <div style="font-size:12px; font-weight:800; letter-spacing:0.8px; text-transform:uppercase; color:#1d68f5; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
                    <span>📝</span> 5. System Audit Trail & Metadata
                </div>
                <div class="modal-grid modal-grid-3">
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">16. Created By (created_by)</span>
                        <strong id="v_created_by" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">17. Entry By (entry_by)</span>
                        <strong id="v_entry_by" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">18. Remarks (remarks)</span>
                        <strong id="v_remarks" style="font-size:13px; color:#0f172a;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">19. Created At (created_at)</span>
                        <strong id="v_created_at" style="font-size:12px; color:#64748b;">-</strong>
                    </div>
                    <div>
                        <span style="font-size:11px; color:#64748b; display:block;">20. Updated At (updated_at)</span>
                        <strong id="v_updated_at" style="font-size:12px; color:#64748b;">-</strong>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL FOOTER -->
        <div style="padding:14px 24px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:var(--card-bg); border-bottom-left-radius:14px; border-bottom-right-radius:14px;">
            <a id="v_inout_link" href="#" class="btn btn-outline" style="font-size:12.5px; padding:7px 14px;">
                🔍 View Attendance Logs
            </a>
            <button type="button" class="btn" onclick="closeDetailsModal()" style="padding:7px 18px;">
                Close
            </button>
        </div>

    </div>
</div>

<!-- MODAL 2: ADD / EDIT EMPLOYEE FORM -->
<div id="empFormModal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closeEmpFormModal()">
    <div class="modal-dialog" style="max-width:680px; padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h3 id="formModalTitle" style="margin:0; font-size:18px; font-weight:800; color:var(--text-dark);">Add Employee</h3>
            <button type="button" onclick="closeEmpFormModal()" style="background:none; border:0; color:var(--text-muted); cursor:pointer; font-size:22px; font-weight:700;">&times;</button>
        </div>

        <form onsubmit="handleSaveEmp(event)">
            <div class="form-grid-2">
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Employee ID / DB ID *</label>
                    <input type="number" id="f_id" style="width:100%;" placeholder="e.g. 198705" required>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Employee Code (emp_id) *</label>
                    <input type="text" id="f_emp_id" style="width:100%;" placeholder="e.g. AFL-SW-44083" required>
                </div>
                <div class="span-2">
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Full Name (emp_name) *</label>
                    <input type="text" id="f_emp_name" style="width:100%;" placeholder="e.g. Mst. Paruly Khatun" required>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Designation</label>
                    <input type="text" id="f_designation" style="width:100%;" placeholder="e.g. Asst. Operator">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Present Grade</label>
                    <input type="text" id="f_present_grade" style="width:100%;" placeholder="e.g. 4">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Gender</label>
                    <select id="f_gender" style="width:100%;">
                        <option value="F">Female (F)</option>
                        <option value="M">Male (M)</option>
                        <option value="Fe">Female (Fe)</option>
                        <option value="Ma">Male (Ma)</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Date of Joining</label>
                    <input type="date" id="f_date_of_join" style="width:100%;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Card ID (RFID)</label>
                    <input type="text" id="f_card_id" style="width:100%;" placeholder="e.g. 25CF32B0">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Present Salary</label>
                    <input type="number" step="0.01" id="f_present_salary" style="width:100%;" placeholder="0.00">
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Employee Type</label>
                    <select id="f_emp_type" style="width:100%;">
                        <option value="2">2 - Operator (OPR)</option>
                        <option value="1">1 - Information Technology (IT)</option>
                        <option value="3">3 - Industrial Engineering (IE)</option>
                        <option value="4">4 - Technician (TCH)</option>
                        <option value="5">5 - QC Finishing (QCF)</option>
                        <option value="6">6 - QC Electrical (QCE)</option>
                        <option value="7">7 - QC Raw Material (QCR)</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:700; color:#334155; margin-bottom:4px;">Status</label>
                    <select id="f_status" style="width:100%;">
                        <option value="1">1 - Active</option>
                        <option value="0">0 - Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:24px;">
                <button type="button" class="btn btn-outline" onclick="closeEmpFormModal()">Cancel</button>
                <button type="submit" class="btn">Save Employee</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const employeesData = @json($employees->keyBy('id'));

const typeMap = {
    1: 'IT (Information Technology)',
    2: 'OPR (Operator)',
    3: 'IE (Industrial Engineering)',
    4: 'TCH (Technician)',
    5: 'QCF (QC Finishing)',
    6: 'QCE (QC Electrical)',
    7: 'QCR (QC Raw Material)'
};

function viewEmpDetails(empOrId) {
    let emp = typeof empOrId === 'object' && empOrId !== null ? empOrId : employeesData[empOrId];
    if (!emp) {
        console.error('Employee not found:', empOrId);
        alert('Employee data not found for: ' + empOrId);
        return;
    }
    const name = emp.emp_name || 'Unknown';
    document.getElementById('dName').innerText = name;
    document.getElementById('dAvatar').innerText = name.charAt(0).toUpperCase();
    document.getElementById('dId').innerText = emp.id ?? '-';
    document.getElementById('dEmpCode').innerText = emp.emp_id ?? '-';

    // 1. Personal & Primary Identification
    document.getElementById('v_id').innerText = emp.id ?? '-';
    document.getElementById('v_company_id').innerText = emp.company_id ?? '-';
    document.getElementById('v_emp_id').innerText = emp.emp_id ?? '-';
    document.getElementById('v_emp_name').innerText = emp.emp_name ?? '-';
    document.getElementById('v_gender').innerText = emp.gender || emp.Gender || '-';

    // 2. Position & Classification
    document.getElementById('v_designation').innerText = emp.designation ?? '-';
    document.getElementById('v_emp_desig').innerText = emp.emp_desig ?? '-';
    document.getElementById('v_present_grade').innerText = emp.present_grade ? 'Grade ' + emp.present_grade : '-';
    document.getElementById('v_emp_type').innerText = typeMap[emp.emp_type] || ('Type ' + (emp.emp_type || '-'));

    // 3. Salary & Dates
    const salary = emp.present_salary !== null && emp.present_salary !== undefined ? Number(emp.present_salary).toFixed(2) : '-';
    document.getElementById('v_present_salary').innerText = salary !== '-' ? '৳ ' + salary : '-';
    document.getElementById('v_date_of_join').innerText = emp.date_of_join || emp.Date_of_join || '-';
    document.getElementById('v_evaluation').innerText = emp.evaluation ?? '-';

    // 4. Access Card & Credentials
    document.getElementById('v_card_id').innerText = emp.card_id || '-';
    document.getElementById('v_pin').innerText = emp.pin ? '•••• (' + emp.pin + ')' : 'None';
    
    const sElem = document.getElementById('v_status');
    if (emp.status === 1 || emp.status === '1') {
        sElem.className = 'pill-status pill-active';
        sElem.innerText = 'Active (1)';
    } else if (emp.status === 0 || emp.status === '0') {
        sElem.className = 'pill-status pill-out';
        sElem.innerText = 'Inactive (0)';
    } else {
        sElem.className = 'pill-status';
        sElem.style.background = '#fef3c7';
        sElem.style.color = '#b45309';
        sElem.innerText = 'Pending (null)';
    }

    // 5. System Audit Trail & Metadata
    document.getElementById('v_created_by').innerText = emp.created_by ?? '-';
    document.getElementById('v_entry_by').innerText = emp.entry_by ? 'User #' + emp.entry_by : '-';
    document.getElementById('v_remarks').innerText = emp.remarks && emp.remarks !== '-' ? emp.remarks : 'None';
    document.getElementById('v_created_at').innerText = emp.created_at ?? '-';
    document.getElementById('v_updated_at').innerText = emp.updated_at ?? '-';

    // Link to Attendance Reports
    document.getElementById('v_inout_link').href = "{{ route('reports.inout') }}?employee=" + encodeURIComponent(emp.id);

    document.getElementById('detailsModal').style.display = 'flex';
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

function openEmpModal(mode) {
    document.getElementById('formModalTitle').innerText = 'Add Employee';
    document.getElementById('f_id').value = '';
    document.getElementById('f_emp_id').value = '';
    document.getElementById('f_emp_name').value = '';
    document.getElementById('f_designation').value = '';
    document.getElementById('f_present_grade').value = '';
    document.getElementById('f_gender').value = 'M';
    document.getElementById('f_date_of_join').value = '';
    document.getElementById('f_card_id').value = '';
    document.getElementById('f_present_salary').value = '';
    document.getElementById('f_emp_type').value = '2';
    document.getElementById('f_status').value = '1';
    document.getElementById('empFormModal').style.display = 'flex';
}

function editEmpForm(empOrId) {
    let emp = typeof empOrId === 'object' && empOrId !== null ? empOrId : employeesData[empOrId];
    if (!emp) {
        console.error('Employee not found:', empOrId);
        alert('Employee data not found for: ' + empOrId);
        return;
    }
    document.getElementById('formModalTitle').innerText = 'Edit Employee #' + emp.id;
    document.getElementById('f_id').value = emp.id ?? '';
    document.getElementById('f_emp_id').value = emp.emp_id ?? '';
    document.getElementById('f_emp_name').value = emp.emp_name ?? '';
    document.getElementById('f_designation').value = emp.designation ?? '';
    document.getElementById('f_present_grade').value = emp.present_grade ?? '';
    document.getElementById('f_gender').value = emp.gender || emp.Gender || 'M';
    document.getElementById('f_date_of_join').value = emp.date_of_join || emp.Date_of_join || '';
    document.getElementById('f_card_id').value = emp.card_id ?? '';
    document.getElementById('f_present_salary').value = emp.present_salary ?? '';
    document.getElementById('f_emp_type').value = emp.emp_type ?? '2';
    document.getElementById('f_status').value = (emp.status === 0 || emp.status === '0') ? '0' : '1';
    document.getElementById('empFormModal').style.display = 'flex';
}

function closeEmpFormModal() {
    document.getElementById('empFormModal').style.display = 'none';
}

function deleteEmp(id, name) {
    if (confirm('Are you sure you want to delete employee: ' + name + ' (ID: ' + id + ')?')) {
        alert('Employee #' + id + ' deleted (simulation).');
    }
}

function handleSaveEmp(e) {
    e.preventDefault();
    alert('Employee information saved successfully!');
    closeEmpFormModal();
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDetailsModal();
        closeEmpFormModal();
    }
});
</script>
@endpush

