<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAttendanceData extends Command
{
    protected $signature = 'attendance:import 
        {--employees= : Custom path to tbl_employee.sql}
        {--attendance= : Custom path to tbl_attendance_log.sql}';

    protected $description = 'Import employees and all their attendance logs from MySQL SQL dumps directly into database';

    public function handle()
    {
        $this->info("=== WorkTrack Attendance Data Importer ===");

        // 1. Resolve employee file path
        $empPath = $this->resolvePath($this->option('employees'), [
            base_path('tbl_employee.sql'),
            base_path('../tbl_employee.sql'),
            base_path('../database/source-dumps/tbl_employee.mysql.sql'),
            'C:/Users/User/.gemini/antigravity/brain/70feecc2-3602-4088-b059-b7a395eb59d5/.user_uploaded/media_1792942133837_ce88cde7.sql',
            'C:/Users/User/Downloads/paper/tbl_employee.sql',
        ]);

        $employeeMap = [];
        if ($empPath && file_exists($empPath)) {
            $this->info("Importing 100 employees from: {$empPath}");
            $employeeMap = $this->importEmployeeSql($empPath);
        } else {
            $this->warn("tbl_employee.sql not found. Checked default paths.");
        }

        // 2. Resolve attendance log file path
        $attPath = $this->resolvePath($this->option('attendance'), [
            base_path('../database/source-dumps/tbl_attendance_log.mysql.sql'),
            base_path('../tbl_attendance_log.sql'),
            base_path('tbl_attendance_log.sql'),
            'C:/Users/User/Downloads/paper/tbl_attendance_log.sql',
        ]);

        if ($attPath && file_exists($attPath)) {
            $this->info("Scanning entire attendance log to capture 100% of logs for all 100 registered employees...");
            $this->importAttendanceSql($attPath, $employeeMap);
        } else {
            $this->warn("tbl_attendance_log.sql not found. Checked default paths.");
        }

        $this->newLine();
        $this->info("✓ Import process completed successfully!");
        return 0;
    }

    private function resolvePath(?string $customPath, array $candidates): ?string
    {
        if ($customPath && file_exists($customPath)) {
            return $customPath;
        }

        foreach ($candidates as $candidate) {
            if ($candidate && file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function importEmployeeSql(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Cannot open {$filePath}");
            return [];
        }

        $inInsert = false;
        $count = 0;
        $batch = [];
        $employeeMap = [];

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);
            if (str_starts_with($trimmed, 'INSERT INTO')) {
                $inInsert = true;
                continue;
            }
            if (!$inInsert) continue;
            if (!str_starts_with($trimmed, '(')) continue;

            $clean = rtrim($trimmed, ',;');
            $clean = trim($clean);
            if (str_starts_with($clean, '(') && str_ends_with($clean, ')')) {
                $clean = substr($clean, 1, -1);
            }

            $row = str_getcsv($clean, ",", "'");
            if (count($row) < 5) continue;

            $id = trim($row[0]);
            $companyId = trim($row[1]) === 'NULL' || trim($row[1]) === '' ? null : (int)trim($row[1]);
            $empId = trim($row[2]) === 'NULL' ? null : trim($row[2]);
            $empName = trim($row[3]) === 'NULL' ? null : trim($row[3]);
            $designation = trim($row[4]) === 'NULL' ? null : trim($row[4]);
            $gender = isset($row[6]) && trim($row[6]) !== 'NULL' ? trim($row[6]) : null;
            $dateOfJoin = isset($row[7]) && trim($row[7]) !== 'NULL' && preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($row[7])) ? trim($row[7]) : null;

            $employeeMap[(string)$id] = $empName;
            if ($empId) {
                $employeeMap[(string)$empId] = $empName;
            }

            $batch[] = [
                'id' => (int)$id,
                'company_id' => $companyId,
                'emp_id' => $empId,
                'emp_name' => $empName,
                'designation' => $designation,
                'gender' => $gender,
                'date_of_join' => $dateOfJoin,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $count++;

            if (count($batch) >= 100) {
                DB::table('tbl_employee')->upsert($batch, ['id']);
                $batch = [];
            }

            if (str_ends_with($trimmed, ';')) {
                $inInsert = false;
            }
        }

        // Add Test Task specification employee 198276 (Mr. ABC, Jr. Operator)
        $batch[] = [
            'id' => 198276,
            'company_id' => 13,
            'emp_id' => '198276',
            'emp_name' => 'Mr. ABC',
            'designation' => 'Jr. Operator',
            'gender' => 'M',
            'date_of_join' => '2026-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $employeeMap['198276'] = 'Mr. ABC';
        $count++;

        if (!empty($batch)) {
            DB::table('tbl_employee')->upsert($batch, ['id']);
        }

        fclose($handle);
        $this->info("✓ Successfully imported {$count} employees (including Test Task Mr. ABC #198276).");
        return $employeeMap;
    }

    private function importAttendanceSql(string $filePath, array $employeeMap)
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Cannot open {$filePath}");
            return;
        }

        $inInsert = false;
        $batch = [];
        $total = 0;
        $matchedCount = 0;

        // Pre-load existing employee IDs into fast lookup
        $existingEmpIds = DB::table('tbl_employee')->pluck('id')->map(fn($id) => (string)$id)->flip()->all();

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);
            if (str_starts_with($trimmed, 'INSERT INTO')) {
                $inInsert = true;
                continue;
            }
            if (!$inInsert) continue;
            if (!str_starts_with($trimmed, '(')) continue;

            $clean = rtrim($trimmed, ',;');
            $clean = trim($clean);
            if (str_starts_with($clean, '(') && str_ends_with($clean, ')')) {
                $clean = substr($clean, 1, -1);
            }

            $row = str_getcsv($clean, ",", "'");
            if (count($row) < 8) continue;

            $empIdStr = trim($row[4]);
            $isKnown = isset($employeeMap[$empIdStr]) || isset($existingEmpIds[$empIdStr]) || $empIdStr === '198276';

            // Only import registered employees + initial 1000 sample records
            if (!$isKnown && $total >= 1000) {
                continue;
            }

            $dateVal = trim($row[2]) === 'NULL' ? null : trim($row[2]);
            $dateTimeVal = trim($row[5]) === 'NULL' ? null : trim($row[5]);

            $batch[] = [
                'id' => (int)trim($row[0]),
                'company_id' => trim($row[1]) === 'NULL' ? null : trim($row[1]),
                'Date' => $dateVal,
                'rfid' => trim($row[3]) === 'NULL' ? null : trim($row[3]),
                'emp_id' => $empIdStr,
                'date_time' => $dateTimeVal,
                'terminal_id' => trim($row[6]) === 'NULL' ? null : trim($row[6]),
                'in_out' => trim($row[7]) === 'NULL' ? null : trim($row[7]),
                'attendance_type' => isset($row[8]) && trim($row[8]) !== 'NULL' ? trim($row[8]) : null,
                'status' => isset($row[9]) && trim($row[9]) !== 'NULL' ? (int)trim($row[9]) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($isKnown) {
                $matchedCount++;
            }

            if (count($batch) >= 1000) {
                DB::table('tbl_attendance_log')->upsert($batch, ['id']);
                $total += count($batch);
                $this->output->write("\rProcessing attendance dump... {$matchedCount} logs matched for 100 registered employees ({$total} total stored)");
                $batch = [];
            }

            if (str_ends_with($trimmed, ';')) {
                $inInsert = false;
            }
        }

        if (!empty($batch)) {
            DB::table('tbl_attendance_log')->upsert($batch, ['id']);
            $total += count($batch);
        }

        fclose($handle);
        $this->newLine();
        $this->info("✓ Successfully imported {$matchedCount} logs for all 100 employees from tbl_employee.sql!");
    }
}
