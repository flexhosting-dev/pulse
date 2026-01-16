# Current PMS App - System Analysis

> *This document analyzes the existing system. See `claude.md` for the new **Pulse** system design.*

## Overview
A web-based Performance Management System used by **Honeyguide Foundation** (Tanzania-based organization) to manage employee performance cycles.

---

## Dashboard
- **Staff Statistics**: Total (53), Male (41), Female (12)
- **Organizational Structure**:
  - Departments: Program (32), Fundraising & Comm (6), Finance and Admin (15)
  - Sub-departments/Units: Portfolios, GameScout, Training, DogHandler, M&E
- **Turnover Tracking**: New Employees vs Exit Employees by department

---

## Menu Structure
```
Performance Management
├── Annual KPIs/KRAs Settings
│   ├── My Annual KPIs/KRAs
│   └── All Annual KPIs/KRAs
├── Mid-Year Performance Appraisals
│   ├── My Mid-Year Performance Appraisals
│   └── All Mid-Year Performance Appraisals
├── Annual Performance Appraisals
│   ├── My Annual Performance Appraisals
│   └── All Annual Performance Appraisals
├── 360 Performance Survey
│   ├── Create Evaluation
│   └── View Evaluation Feedback
└── Performance Improvement Plan (PIP)
    ├── Plans
    └── Review
```

---

## Annual KPI/KRA Settings

### List View Features
- Document numbering: `APO/2025/25`
- Data table with: Copy, Excel, PDF, Print, Column visibility
- Period filtering (date range)
- Columns: Document No., Staff, Objective Period, Completion Date, Status, Approvals, Actions

### Statuses
CREATED → SUBMITTED → APPROVED

### Approval Badges
SUP (Supervisor), HRM (HR Manager) - green checkmark when approved

### Performance Objective Template (4 Parts)

| Part | Content |
|------|---------|
| **Part I: Performance Standards** | Performance Objectives, Key Activities (#1.1, 2.1, etc.), Success Measures, Weight(%), Target Date |
| **Part II: Behavioural Competencies** | Competencies, Description, Weight(%) |
| **Part III: Training & Development** | Objectives, Training Activity, Completion Deadline |
| **Part IV: Career Planning** | Career Objectives, Activity, Completion Deadline |

### Validation
Total Weight must equal 100%

### KPI Mode
"No cascading" option available

---

## Mid-Year Performance Reviews

### Document Numbering
`APOHY/2026/2`

### Creation Flow
1. Select Performance Objective Template (links to approved annual KPIs)
2. Set Half Year Performance Period
3. Set Completion Date

### Review Form Structure
- Organization header with logo, address, contact
- Employee info (Name, Position, Department, Supervisor)

### Rating Scale

| Points | Score | Description | Action |
|--------|-------|-------------|--------|
| 4 | 85%+ | As Expected - Meet job standards | Recognition |
| 3 | 65-84% | As Expected - Meet job standards | Continue development |
| 2 | 40-64% | Below expectation | PIP needed |
| 1 | 39% and below | Poor Performance | Consider termination |

### Part I Rating Columns
- Performance Objectives
- Success Measures KPI
- Weight(%)
- Employee Rate (input)
- Employee Comments (textarea)
- Supervisor Rate (input)
- Supervisor Comments (textarea)
- Final Rate

### Calculated Totals
- Employee Total Rate
- Supervisor Total Rate
- Total Final Rate

### Part II (Behavioural Competencies)
Same rating structure with Employee/Supervisor rates and comments

### Part III (Training Review)
- Performance Objectives
- Recommended/Required Training
- Complete/Incomplete checkboxes
- Reason if Incomplete

### Part IV (Career Review)
- Career Objectives
- Activity
- Achieved/Not Achieved
- Reason if Not Achieved

### Employee Comment Section
Bottom section with Save and Submit buttons

---

## Annual Performance Appraisals

Similar to Mid-Year with additional:

### Overall Assessment Section
- Overall Performance Standards Score: X%
- Overall Behavioural Competencies Score: X%
- Overall Performance Score: X%
- Overall Performance Rate (1-4 based on score)

---

## Approval Workflow

### Three-tier Approval
1. **Prepared By** - Employee (with signature placeholder, name, date)
2. **Supervisor/HoD** - Signature, name, date, Comments button
3. **HR/Administration Manager** - Signature, name, date, Comments button

### Features
- "Open Approvals Log" button for audit trail
- "Approvals Completed" indicator when all signed

---

## Performance Improvement Plan (PIP)

### Document Numbering
`HR-PIP/2024/22`

### Creation Form
- Select Staff (dropdown)
- PIP Start Date
- PIP End Date

### PIP Detail View
- Plan Number, Staff, Start Date, End Date, Status

### Improvement Areas Table

| Column | Description |
|--------|-------------|
| Skills/Behaviours to Improve | What needs improvement (specific) |
| Action to be Taken | Steps for improvement |
| Development Opportunities/Resources | Training, support needed |
| Date to be Completed | Target deadline |
| Success Measure | How improvement will be measured |

### Statuses
DRAFT → SUBMITTED → ACCEPTED → (IN_PROGRESS) → COMPLETED

### Approval
Prepared By → HR Manager (shows "Waiting for HR Manager Approval")

---

## Technical Observations

- **UI Framework:** Bootstrap-based with green accent color
- **Data Tables:** jQuery DataTables with export functionality
- **Date Format:** YYYY-MM-DD
- **Form Pattern:** Multi-step forms with Save Changes per section
- **Print Feature:** Available on detail views
- **Responsive:** Yes (sidebar navigation)

---

## Identified Gaps/Limitations

1. No visible notification system
2. No continuous check-in/monitoring between reviews
3. No counselling session documentation
4. No version history for objectives
5. Limited reporting/analytics beyond dashboard
6. No visible audit log in main UI (only "Open Approvals Log")
7. 360 Survey details not shown in screenshots

---

*Analysis Date: 2026-01-16*
*Source: PMS HR App Screenshots*
