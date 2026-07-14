# SGR Parking Revenue Management System — Developer Specification

**Document version:** 1.0
**Date:** 14 July 2026
**Reference data source:** Daily Car Parking Revenue Collection Report — Samia Suluhu SGR Station, Dodoma (Lot 2), Booth 1 & 2, July 2025 – July 2026

---

## 1. Purpose & Overview

The company operates car parking facilities at SGR stations (e.g., Samia Suluhu SGR Station — Dodoma). Currently, daily revenue collections are recorded manually in Excel workbooks, with one sheet per month and one row per booth-shift. This system will digitize the entire workflow: registration of parking lots and stations, target setting, daily report submission (manual entry and Excel upload), and management dashboards with charts.

The system shall be a web application with Role-Based Access Control (RBAC) supporting exactly four roles.

---

## 2. User Roles & Access Control (RBAC)

The system shall enforce a strict role hierarchy. Each user account has exactly one role.

### 2.1 Company Owner (single account)

The highest-level user. The system shall allow **only one active Company Owner account**.

Permissions:
- Full read access to everything in the system (all lots, all stations, all reports, all targets, all users).
- View the company-wide dashboard and all analytics charts.
- Track company targets vs. achievements across all stations.
- Manage system users (create, deactivate, reset passwords, change roles).
- Export any report (Excel / PDF / CSV).
- Manage company profile information (name, logo, contacts).

### 2.2 Finance Officer (single account)

One person responsible for finance. The system shall allow one Finance Officer account (configurable to allow more later).

Permissions:
- View revenue from all stations (read-only on operational data).
- Track financial targets and compare actual vs. target performance.
- Review daily collections, deposits, and differences (variance monitoring — see §7.3).
- Generate and export financial reports (Excel and PDF).
- Approve/flag daily reports with discrepancies between *Amount Collected* and *Amount Deposited*.
- **Cannot** create lots/stations, manage users, or submit daily reports.

### 2.3 Administrator Manager

Responsible for system administration and operational setup.

Permissions:
- Register and manage parking lots (CRUD).
- Create and manage stations under each lot (CRUD).
- Assign supervisors to stations (one supervisor may be assigned to one or more stations).
- Configure targets (both Contractual Set Targets and Company Set Targets).
- Review and approve/reject daily reports submitted by supervisors.
- Maintain system settings (tariffs, booth definitions, shift definitions, fiscal year).
- **Cannot** manage the Company Owner account or delete financial records.

### 2.4 Supervisor

Manages daily operations at their assigned station(s).

Permissions:
- View only their assigned station(s) — data isolation is mandatory; a supervisor must never see other stations' data.
- Submit daily operational reports via web form.
- **Upload daily reports as Excel files** in the company's standard format (see §7).
- View their station's dashboard: performance, target progress, submission history.
- Edit a submitted report only while it is still in *Pending* status; once approved it becomes read-only.

### 2.5 Authentication & security requirements

- Login with email/username + password; passwords hashed (bcrypt/argon2).
- Session or JWT-based auth with role claims; all API endpoints authorize per role.
- Audit log: every create/update/delete and every report approval records user, timestamp, and action.
- Password reset flow and account deactivation (soft delete — never hard-delete users referenced by records).

---

## 3. Parking Lot & Station Management

### 3.1 Hierarchy

```
Company
 └── Parking Lot (e.g., "Samia Suluhu SGR Station — Dodoma, Lot 2")
      └── Station / Booth (e.g., "Booth 1 (Main)", "Booth 2")
           └── Daily Reports, Targets, Cashier records
```

### 3.2 Parking Lot entity

| Field | Type | Notes |
|---|---|---|
| id | UUID | PK |
| name | string | e.g., "Samia Suluhu SGR Station — Dodoma Lot 2" |
| location | string | e.g., "Dodoma" |
| address | string | optional |
| capacity | integer | vehicle capacity |
| status | enum | Active / Inactive |
| registered_at | date | |

### 3.3 Station entity

| Field | Type | Notes |
|---|---|---|
| id | UUID | PK |
| parking_lot_id | FK | belongs to one lot |
| name | string | e.g., "Booth 1 (Main)", "Booth 2" |
| supervisor_id | FK → users | assigned supervisor |
| status | enum | Active / Inactive |
| notes | text | |

A lot has many stations; deleting a lot must be blocked if it has stations with historical reports (use deactivation instead).

---

## 4. Target Goal Management

Every station (and optionally every lot and the company as a whole) must have targets. There are **two target types**, stored in one table distinguished by `target_type`:

### 4.1 Contractual Set Target

Targets defined by contracts/agreements (e.g., with the railway authority). Examples:
- Monthly Revenue Target: 10,000,000 TZS
- Monthly Vehicle Target: 5,000 vehicles

### 4.2 Company Set Target

Internal targets to push performance beyond the contract. Examples:
- Increase monthly revenue by 20%
- Reach 8,000 vehicles per month

### 4.3 Target entity

| Field | Type | Notes |
|---|---|---|
| id | UUID | PK |
| scope | enum | company / parking_lot / station |
| scope_id | FK | nullable when scope = company |
| target_type | enum | `contractual` / `company_set` |
| metric | enum | revenue_tzs / vehicle_count |
| period | enum | daily / weekly / monthly / yearly |
| target_value | decimal | |
| start_date, end_date | date | validity window |
| created_by | FK → users | Administrator Manager |

### 4.4 Computed fields (displayed everywhere targets appear)

- **Achieved Value** — sum of approved daily report amounts within the period.
- **Remaining Balance** — target_value − achieved.
- **Completion %** — achieved ÷ target_value × 100.
- **Status** — e.g., On Track (≥ expected pro-rata), Behind, Achieved, Missed.

Both target types must be shown **side by side** on dashboards so management can see contract compliance and internal ambition simultaneously.

---

## 5. Dashboard & Analytics

### 5.1 Company dashboard (Owner, Finance Officer, Admin Manager)

KPI cards:
- Total Parking Lots, Total Stations, Active Supervisors
- Total Revenue (today / this week / this month / this year)
- Total Deposits and Total Difference (collected − deposited)
- Total Vehicles
- Target Achievement Rate (contractual and company-set, shown separately)
- Reports pending approval

### 5.2 Supervisor dashboard

- Assigned station summary, today's submission status, month-to-date revenue, target progress bar, recent uploads and their validation results.

### 5.3 Required charts (interactive; filterable by date range, lot, station)

1. **Revenue Performance Chart** — line/area chart of daily, weekly, and monthly revenue trends; toggle between Amount Collected and Amount Deposited.
2. **Station Comparison Chart** — bar chart comparing stations/booths by revenue, vehicle count, and target achievement %.
3. **Target Achievement Chart** — target vs. actual (grouped bars or gauge), completion %, trend over time; separate series for contractual vs. company-set targets.
4. **Supervisor Performance Chart** — reports submitted on time vs. late vs. missing, per supervisor; station performance under each supervisor.
5. **Parking Utilization Chart** — vehicle flow trends, peak days, and (where shift data allows) day-shift vs. night-shift comparison.
6. **Collection vs. Deposit Variance Chart** — highlights any day where Difference ≠ 0 (critical for the Finance Officer).

Recommended stack: Chart.js or Recharts on the frontend; all chart data served by aggregate API endpoints, never computed in the browser from raw rows.

---

## 6. Daily Report Management (manual entry)

Supervisors submit daily reports through a form containing:

- Report Date
- Station / Booth
- Shift (Day 08:00–20:00 / Night 20:00–08:00) — inferred from the existing data where Time In/Out are consistently 08:00 and 20:00
- Number of Vehicles
- Amount Collected (TZS)
- Amount Deposited (TZS)
- Difference (auto-calculated: collected − deposited)
- Control No. (GePG control number)
- Receipt No. (GePG receipt, e.g., `AGNGEPG215055095`)
- Cashier Name
- Control No. Status (Provided / Pending)
- Expenses and Net Revenue (optional fields)
- Challenges / Comments
- Supporting attachments (images, PDF)

Workflow: **Draft → Submitted (Pending) → Approved / Rejected** (reviewed by Administrator Manager; flagged variances also visible to Finance Officer). One report per station-shift-date must be enforced (unique constraint).

---

## 7. Excel Report Upload Module

This is a core feature. Supervisors upload the company's existing Excel workbook and the system parses it automatically.

### 7.1 Actual file format (from the reference workbook)

- One **workbook** per station, with **one sheet per month** (e.g., `SSH BOOTH 1 AND 2 JULY`, `SSH BOOTH 1 & 2 FEB 2026`).
- Rows 1–3: title block — report title, report date, and location (`DODOMA`).
- Row 4: header row with these columns:

| Column | Meaning | Parsing notes |
|---|---|---|
| S/N | serial number | ignore for storage; may repeat/skip — do not rely on it |
| DATE IN | shift start date | Excel serial date (e.g., 45852) → convert |
| DATE OUT | shift end date | night shifts end the next day |
| TIME IN | shift start time | may be a text like `08:01:AM` **or** an Excel time fraction like `0.8333…` — parser must handle both |
| TIME OUT | shift end time | same dual format |
| AMOUNT COLLECTED | TZS integer | |
| AMOUNT DEPOSITED | TZS integer | |
| DIFFERENCE | collected − deposited | often shown as ` - ` (text) when zero — treat as 0 |
| CONTROL NO. | GePG control number | numeric string; same control no. covers multiple rows |
| RECEIPT NO. | GePG receipt code | alphanumeric (note real typos exist, e.g., letter `O` vs zero) |
| CASHIER NAME | string | |
| CONTROL NO. STATUS | Provided / Pending | |
| COMMENTS | booth identifier | free text: "Booth 1 Main", "Booth 2", including misspellings ("Boooth 2", "Both 1 Main") — normalize with fuzzy matching to a booth ID |

- Final row: `TOTAL` with summed collected/deposited amounts — use it as a **checksum**, not as data.

### 7.2 Import pipeline (system behaviour)

1. Supervisor selects station and uploads `.xlsx`.
2. System reads all sheets (or lets the supervisor pick a month/sheet).
3. Row-by-row parsing with the rules above; each valid row becomes a `daily_collection` record.
4. **Validation before saving:**
   - Dates parse correctly and fall within the sheet's month.
   - Amounts are positive numbers; Difference recomputed and compared.
   - Duplicate detection: same station + date + shift + booth already in the DB → flag as duplicate (skip or overwrite with confirmation).
   - Sheet TOTAL vs. sum of parsed rows — mismatch triggers a warning.
   - Missing Control No. or Receipt No. → row flagged, not rejected.
5. **Preview screen:** show parsed rows with green (valid) / yellow (warnings) / red (errors) status before the supervisor confirms import.
6. On confirmation, records are saved with status *Pending* and enter the same approval workflow as manual reports.
7. The original file is stored as an attachment for audit purposes.
8. Import summary (rows imported, skipped, flagged) is logged and visible to the Administrator Manager.

### 7.3 Variance rule (Finance)

Any imported or manual record where `AMOUNT COLLECTED ≠ AMOUNT DEPOSITED` must be automatically flagged and surfaced on the Finance Officer's dashboard with the cashier name, date, booth, and difference amount.

---

## 8. Reporting Module

Available reports (role-restricted as described in §2):

- Daily Station Report (per station, per date, with booth/shift breakdown)
- Monthly Revenue Report (per station, per lot, company-wide)
- Target Achievement Report (contractual and company-set, side by side)
- Supervisor Performance Report (submission timeliness, approvals, rejections)
- Parking Utilization Report
- Financial Summary Report (collections, deposits, variances, expenses, net revenue)
- Station Comparison Report

Export options for every report: **Excel, PDF, CSV**. Exports must respect the active filters (date range, lot, station).

---

## 9. Data Model Summary (core tables)

```
users(id, name, email, phone, role[owner|finance|admin_manager|supervisor],
      password_hash, status, created_at)

parking_lots(id, name, location, address, capacity, status, registered_at)

stations(id, parking_lot_id, name, supervisor_id, status)

booths(id, station_id, name)                     -- e.g., Booth 1 Main, Booth 2

targets(id, scope, scope_id, target_type[contractual|company_set],
        metric[revenue|vehicles], period, target_value,
        start_date, end_date, created_by)

daily_reports(id, station_id, report_date, submitted_by, status
              [draft|pending|approved|rejected], vehicle_count, expenses,
              net_revenue, comments, source[manual|excel], file_id, reviewed_by,
              reviewed_at)

daily_collections(id, daily_report_id, booth_id, shift[day|night],
                  date_in, date_out, time_in, time_out,
                  amount_collected, amount_deposited, difference,
                  control_no, receipt_no, cashier_name,
                  control_no_status[provided|pending], comments, flags)

attachments(id, daily_report_id, file_path, uploaded_by, uploaded_at)

audit_logs(id, user_id, action, entity, entity_id, details, created_at)
```

---

## 10. Non-Functional Requirements

- **Tech suggestions:** Laravel/Django/Node backend + REST API; React/Vue frontend; MySQL/PostgreSQL; Excel parsing via PhpSpreadsheet / openpyxl / SheetJS depending on stack.
- Currency: TZS, formatted with thousands separators; timezone: Africa/Dar_es_Salaam (EAT).
- Language: English UI (Swahili optional as phase 2 i18n).
- Responsive design — supervisors will often use phones.
- Backups of database and uploaded files; uploads limited to `.xlsx` (and images/PDF for attachments) with size cap (e.g., 10 MB).
- Performance: dashboard aggregates should load in < 2 s for one year of data (pre-aggregate daily summaries if needed).

---

## 11. System Objectives (summary)

The system will enable the company to:

1. Manage all parking lots, stations, and booths from one platform.
2. Monitor revenue collection and bank deposits in real time and catch variances immediately.
3. Track both contractual and internal targets with clear achievement percentages.
4. Replace manual Excel consolidation with automated upload, validation, and approval.
5. Hold supervisors and cashiers accountable through audit trails and performance charts.
6. Support data-driven decisions through dashboards and exportable reports.
