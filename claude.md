# Pulse - Performance Management System

## Design Document

## Table of Contents
1. [Executive Summary](#executive-summary)
2. [Performance Management Philosophy](#performance-management-philosophy)
3. [Current System Analysis](#current-system-analysis)
4. [New System Architecture](#new-system-architecture)
5. [Database Design](#database-design)
6. [Module Breakdown](#module-breakdown)
7. [Workflow Definitions](#workflow-definitions)
8. [API Design](#api-design)
9. [Security Considerations](#security-considerations)
10. [UI/UX Improvements](#uiux-improvements)
11. [Implementation Roadmap](#implementation-roadmap)

---

## Executive Summary

**Pulse** is a modern, Symfony-based Performance Management System designed to replace the existing HR performance tracking application at Honeyguide Foundation.

The name "Pulse" reflects our philosophy: performance management should be a continuous rhythm of check-ins, feedback, and growth—not just an annual event.

The new system will maintain all existing functionality while introducing improvements in architecture, scalability, user experience, and maintainability.

### Key Objectives
- Modernize the codebase using Symfony 7.x best practices
- Improve database design for better data integrity and reporting
- Enhance the approval workflow with better state management
- Implement a cleaner, more intuitive user interface
- Add comprehensive audit logging and reporting capabilities
- Support multi-tenancy for potential SaaS deployment

---

## Performance Management Philosophy

> *Based on Honeyguide Foundation's Performance Management Training (January 2026)*

### Core Definition

**Performance Management** is the continuous process of:
- **Planning** performance expectations
- **Monitoring** progress throughout the period
- **Coaching** and supporting employees
- **Reviewing/Appraising** performance outcomes
- **Recognizing** achievements and addressing gaps

> **Key Insight:** Performance management is NOT just about annual reviews—it's an ongoing, continuous process that helps both individuals and the organization achieve their goals.

### The Performance Management Cycle

The system must support this continuous cycle:

```
       ┌─────────────┐
       │  1. GOAL    │
       │   SETTING   │
       └──────┬──────┘
              │
    ┌─────────▼─────────┐
    │   5. RECOGNITION  │◄────────────────────┐
    │   Reward & improve│                     │
    └─────────┬─────────┘                     │
              │                               │
       ┌──────▼──────┐                 ┌──────┴──────┐
       │ 2. MONITOR  │                 │  4. REVIEW  │
       │Track progress│                 │   Assess    │
       └──────┬──────┘                 └──────▲──────┘
              │                               │
              └──────────┐    ┌───────────────┘
                         │    │
                    ┌────▼────▼────┐
                    │  3. COACHING │
                    │Support & guide│
                    └──────────────┘
```

### System Design Principles

Based on organizational best practices, the system enforces:

| Principle | Description | How the System Enforces It |
|-----------|-------------|----------------------------|
| **Fairness** | Everyone evaluated using same criteria | Standardized rating scales, weighted objectives |
| **Consistency** | Standards applied uniformly | Workflow-enforced approval processes |
| **Transparency** | Clear processes everyone understands | Visible statuses, audit trails, notifications |
| **Predictability** | Outcomes based on documented criteria | Calculated scores based on agreed weights |

### SMART Goals Framework

All objectives created in the system should be validated against the SMART framework:

| Letter | Meaning | System Implementation |
|--------|---------|----------------------|
| **S** | Specific - Clear & well-defined | Required description field with minimum length |
| **M** | Measurable - Quantifiable criteria | Mandatory "Success Measures" field |
| **A** | Achievable - Realistic & attainable | Supervisor review during approval |
| **R** | Relevant - Aligned with objectives | Link to departmental/organizational goals |
| **T** | Time-bound - Clear deadlines | Required "Target Date" field |

### Performance Standard Criteria

The system captures multiple dimensions of performance:

| Criterion | Description | Example Measures |
|-----------|-------------|------------------|
| **Quality** | How well the work is done | Accuracy rate, error rate, satisfaction scores |
| **Quantity** | How much work is completed | Number of units, reports, projects completed |
| **Time** | When the work is completed | Deadlines met, turnaround time |
| **Cost** | Resources used to complete work | Budget adherence, efficiency metrics |
| **Safety** | Compliance with safety standards | Incident rates, compliance scores |

### Results vs Activities

> **Critical Principle:** The system focuses on **RESULTS** (outcomes achieved), not just **ACTIVITIES** (tasks performed). Being busy is not the same as being productive.

| Activities (What you DO) | Results (What you ACHIEVE) |
|--------------------------|----------------------------|
| Attending meetings | Decisions made and implemented |
| Making calls | Partnerships established |
| Writing reports | Insights that drive action |
| Conducting training | Skills improved, behavior changed |

**System Design Implication:** Success measures must describe observable outcomes, not just activities performed.

### Modern Performance Management Approach

The system implements modern PM principles over traditional approaches:

| Traditional Approach | Modern Approach (Our System) |
|---------------------|------------------------------|
| Annual review only | Continuous feedback & check-ins |
| Past-focused (What went wrong) | Future-focused (How to improve) |
| Form-filling exercise | Growth-oriented conversations |
| Manager-driven | Collaborative partnership |
| Rating & ranking | Development & coaching |
| One-way communication | Two-way dialogue |

**System Features Supporting Modern PM:**
- Mid-year reviews for continuous feedback
- Comments fields for two-way dialogue
- Training & Development tracking
- Career Planning section
- Counselling documentation

### Consequence Management Framework

The system must support appropriate consequences for both good and poor performance:

#### Good Performance Pathway
```
Recognition → Rewards → Retention Efforts → Career Advancement → Increased Responsibilities
```

**System Support:**
- Performance history visible for promotion decisions
- Achievements documented in appraisals
- Training completion tracked for career growth

#### Poor Performance Pathway
```
Help & Support → One-on-ones → Counselling → Warnings → PIP → Manage Out (Last Resort)
```

**System Support:**
- Counselling session documentation
- Structured Performance Improvement Plans (PIPs)
- Progress tracking within PIP periods
- Clear escalation workflows

### Performance Counselling

Counselling is a supportive, documented conversation to:
- Correct behavior or performance gaps
- Identify root causes
- Agree on expectations
- Provide support (skills, tools, clarity)
- Prevent disciplinary action

> **Key Formula:** Counselling = Support + Accountability

#### Counselling Process (System-Supported)

| Step | Action | System Feature |
|------|--------|----------------|
| 1 | **Prepare** - Gather facts, data, examples | Performance history, previous appraisals |
| 2 | **Open Discussion** - Create safe space | Private counselling record |
| 3 | **Describe Issue** - Focus on behaviors/facts | Specific objective ratings |
| 4 | **Listen** - Allow employee perspective | Comments fields |
| 5 | **Identify Root Causes** - Explore gaps | Free-text analysis |
| 6 | **Agree Expectations** - Set SMART goals | PIP improvement areas |
| 7 | **Provide Support** - Identify resources | Training recommendations |
| 8 | **Document** - Record discussion | Counselling log entity |
| 9 | **Follow Up** - Schedule check-ins | PIP review dates |

### PIP Best Practices (Built into System)

When a Performance Improvement Plan is needed:

1. **Be Specific** - Clear performance gaps with measurable targets
2. **Set Realistic Targets** - Challenging but achievable within timeframe
3. **Provide Genuine Support** - Link to training resources
4. **Schedule Regular Check-ins** - Built-in review milestones
5. **Document Everything** - Complete audit trail
6. **Be Fair and Consistent** - Standard workflow for all employees

---

## Current System Analysis

### Existing Features Identified

#### 1. Dashboard
- Staff statistics (total, male, female counts)
- Department/Sub-department breakdown with staff counts
- Staff turnover summary (hired/exit by department)

#### 2. Annual KPIs/KRAs Module
- Personal and organization-wide KPI views
- Document numbering system (e.g., APO/2025/25)
- Four-part performance objective structure:
  - **Part I**: Performance Standards (Objectives, Activities, Success Measures, Weight%, Target Date)
  - **Part II**: Behavioural Competencies (Competencies, Description, Weight%)
  - **Part III**: Training & Development Objectives
  - **Part IV**: Career Planning
- Two-level approval workflow (Supervisor + HR Manager)
- Data export (Copy, Excel, PDF, Print)

#### 3. Mid-Year Performance Appraisals
- Links to annual goals templates
- Employee self-rating with comments
- Supervisor rating with comments
- Final rate calculation
- 4-point rating scale:
  - 4: 85%+ (Meets/Exceeds standards)
  - 3: 65-84% (As Expected)
  - 2: 40-64% (Below expectation - PIP needed)
  - 1: 39% and below (Poor - Consider termination)

#### 4. Annual Performance Appraisals
- Similar to mid-year with overall assessment
- Calculates Overall Performance Standards Score
- Calculates Overall Behavioural Competencies Score
- Derives Overall Performance Score and Rating

#### 5. 360 Performance Survey
- Create evaluations
- View evaluation feedback

#### 6. Performance Improvement Plan (PIP)
- Staff selection with date range
- Structured improvement areas:
  - Skills/Behaviours to Improve
  - Actions to be Taken
  - Development Opportunities/Resources
  - Completion Dates
  - Success Measures
- HR Manager approval workflow

### Current System Limitations
1. Tightly coupled components
2. Limited audit trail capabilities
3. Basic reporting features
4. No notification system visible
5. Manual document numbering
6. Limited filtering/search on list views
7. No version history for objectives

---

## New System Architecture

### Technology Stack

```
┌─────────────────────────────────────────────────────────────┐
│                      Frontend Layer                          │
│  Symfony UX (Turbo + Stimulus) / Bootstrap 5 / Chart.js     │
├─────────────────────────────────────────────────────────────┤
│                    Application Layer                         │
│           Symfony 7.x (PHP 8.3+) / Doctrine ORM             │
├─────────────────────────────────────────────────────────────┤
│                      Service Layer                           │
│  Workflow Component │ Messenger │ Mailer │ Security         │
├─────────────────────────────────────────────────────────────┤
│                    Data Layer                                │
│              PostgreSQL / Redis (Cache)                      │
└─────────────────────────────────────────────────────────────┘
```

### Symfony Components to Utilize

| Component | Purpose |
|-----------|---------|
| **Symfony Workflow** | Manage approval state machines |
| **Symfony Messenger** | Async processing (notifications, reports) |
| **Symfony Mailer** | Email notifications |
| **Symfony Security** | Authentication, authorization, voters |
| **Symfony UX Turbo** | SPA-like experience without full JS framework |
| **Symfony UX Stimulus** | JavaScript controllers |
| **Doctrine ORM** | Database abstraction |
| **API Platform** | REST API (optional, for integrations) |

### Directory Structure

```
src/
├── Controller/
│   ├── Dashboard/
│   ├── Kpi/
│   ├── Appraisal/
│   ├── Survey/
│   ├── Pip/
│   └── Admin/
├── Entity/
│   ├── User/
│   ├── Organization/
│   ├── Performance/
│   └── Workflow/
├── Repository/
├── Service/
│   ├── Document/
│   ├── Rating/
│   ├── Notification/
│   └── Report/
├── Workflow/
│   ├── ApprovalWorkflow.php
│   └── PipWorkflow.php
├── EventSubscriber/
├── Form/
├── Twig/
│   └── Components/
└── DataFixtures/
```

---

## Database Design

### Entity Relationship Diagram (Conceptual)

```
┌──────────────┐     ┌──────────────────┐     ┌─────────────────┐
│    User      │────<│  UserDepartment  │>────│   Department    │
└──────────────┘     └──────────────────┘     └─────────────────┘
       │                                              │
       │                                              │
       ▼                                              ▼
┌──────────────────┐                        ┌─────────────────┐
│ PerformancePlan  │                        │   SubDepartment │
└──────────────────┘                        └─────────────────┘
       │
       ├──────────────────┬──────────────────┬──────────────────┐
       ▼                  ▼                  ▼                  ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ Objective    │  │ Competency   │  │ Training     │  │ CareerGoal   │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
       │
       ▼
┌──────────────────┐
│ KeyActivity      │
└──────────────────┘
```

### Core Entities

#### 1. User Entity
```php
#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 100)]
    private string $firstName;

    #[ORM\Column(length: 100)]
    private string $lastName;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $employeeNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $position = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(enumType: Gender::class)]
    private Gender $gender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $supervisor = null;

    #[ORM\ManyToOne(targetEntity: Department::class)]
    private ?Department $department = null;

    #[ORM\ManyToOne(targetEntity: SubDepartment::class)]
    private ?SubDepartment $subDepartment = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $hireDate = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $exitDate = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
}
```

#### 2. PerformancePlan Entity (Annual KPIs/KRAs)
```php
#[ORM\Entity]
#[ORM\Table(name: 'performance_plans')]
class PerformancePlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $documentNumber;  // APO/2025/25

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $employee;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $periodStart;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $periodEnd;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $completedOn = null;

    #[ORM\Column(enumType: PlanStatus::class)]
    private PlanStatus $status = PlanStatus::DRAFT;

    #[ORM\Column(enumType: KpiMode::class)]
    private KpiMode $kpiMode = KpiMode::NO_CASCADING;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: Objective::class, cascade: ['persist', 'remove'])]
    private Collection $objectives;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: BehaviouralCompetency::class, cascade: ['persist', 'remove'])]
    private Collection $competencies;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: TrainingObjective::class, cascade: ['persist', 'remove'])]
    private Collection $trainingObjectives;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: CareerGoal::class, cascade: ['persist', 'remove'])]
    private Collection $careerGoals;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: Approval::class, cascade: ['persist', 'remove'])]
    private Collection $approvals;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
}
```

#### 3. Objective Entity (Part I: Performance Standards)
```php
#[ORM\Entity]
#[ORM\Table(name: 'objectives')]
class Objective
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PerformancePlan::class, inversedBy: 'objectives')]
    #[ORM\JoinColumn(nullable: false)]
    private PerformancePlan $plan;

    #[ORM\Column(type: 'smallint')]
    private int $sortOrder;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $weight;  // Weight percentage (total should = 100)

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $targetDate;

    #[ORM\OneToMany(mappedBy: 'objective', targetEntity: KeyActivity::class, cascade: ['persist', 'remove'])]
    private Collection $keyActivities;
}
```

#### 4. KeyActivity Entity
```php
#[ORM\Entity]
#[ORM\Table(name: 'key_activities')]
class KeyActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Objective::class, inversedBy: 'keyActivities')]
    #[ORM\JoinColumn(nullable: false)]
    private Objective $objective;

    #[ORM\Column(length: 10)]
    private string $activityNumber;  // e.g., "1.1", "2.3"

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'text')]
    private string $successMeasures;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private string $weight;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $targetDate;
}
```

#### 5. Appraisal Entity (Mid-Year / Annual Reviews)
```php
#[ORM\Entity]
#[ORM\Table(name: 'appraisals')]
class Appraisal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $documentNumber;  // APOHY/2026/2 or APOA/2026/1

    #[ORM\ManyToOne(targetEntity: PerformancePlan::class)]
    #[ORM\JoinColumn(nullable: false)]
    private PerformancePlan $performancePlan;

    #[ORM\Column(enumType: AppraisalType::class)]
    private AppraisalType $type;  // MID_YEAR or ANNUAL

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $periodStart;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $periodEnd;

    #[ORM\Column(enumType: AppraisalStatus::class)]
    private AppraisalStatus $status = AppraisalStatus::CREATED;

    #[ORM\OneToMany(mappedBy: 'appraisal', targetEntity: ObjectiveRating::class, cascade: ['persist', 'remove'])]
    private Collection $objectiveRatings;

    #[ORM\OneToMany(mappedBy: 'appraisal', targetEntity: CompetencyRating::class, cascade: ['persist', 'remove'])]
    private Collection $competencyRatings;

    #[ORM\OneToMany(mappedBy: 'appraisal', targetEntity: TrainingReview::class, cascade: ['persist', 'remove'])]
    private Collection $trainingReviews;

    #[ORM\OneToMany(mappedBy: 'appraisal', targetEntity: CareerReview::class, cascade: ['persist', 'remove'])]
    private Collection $careerReviews;

    // Calculated scores
    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $performanceStandardsScore = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $competenciesScore = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $overallScore = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $overallRating = null;  // 1-4

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $employeeComment = null;

    #[ORM\OneToMany(mappedBy: 'appraisal', targetEntity: Approval::class, cascade: ['persist', 'remove'])]
    private Collection $approvals;
}
```

#### 6. ObjectiveRating Entity
```php
#[ORM\Entity]
#[ORM\Table(name: 'objective_ratings')]
class ObjectiveRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Appraisal::class, inversedBy: 'objectiveRatings')]
    #[ORM\JoinColumn(nullable: false)]
    private Appraisal $appraisal;

    #[ORM\ManyToOne(targetEntity: KeyActivity::class)]
    #[ORM\JoinColumn(nullable: false)]
    private KeyActivity $keyActivity;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $employeeRate = null;  // 1-4

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $employeeComment = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $supervisorRate = null;  // 1-4

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $supervisorComment = null;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $finalRate = null;  // Typically supervisor rate or agreed rate
}
```

#### 7. PerformanceImprovementPlan Entity (PIP)
```php
#[ORM\Entity]
#[ORM\Table(name: 'performance_improvement_plans')]
class PerformanceImprovementPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $planNumber;  // HR-PIP/2024/22

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $employee;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $endDate;

    #[ORM\Column(enumType: PipStatus::class)]
    private PipStatus $status = PipStatus::DRAFT;

    #[ORM\OneToMany(mappedBy: 'pip', targetEntity: ImprovementArea::class, cascade: ['persist', 'remove'])]
    private Collection $improvementAreas;

    #[ORM\OneToMany(mappedBy: 'pip', targetEntity: Approval::class, cascade: ['persist', 'remove'])]
    private Collection $approvals;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
```

#### 8. ImprovementArea Entity
```php
#[ORM\Entity]
#[ORM\Table(name: 'improvement_areas')]
class ImprovementArea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PerformanceImprovementPlan::class, inversedBy: 'improvementAreas')]
    #[ORM\JoinColumn(nullable: false)]
    private PerformanceImprovementPlan $pip;

    #[ORM\Column(type: 'text')]
    private string $skillsToImprove;

    #[ORM\Column(type: 'text')]
    private string $actionToBeTaken;

    #[ORM\Column(type: 'text')]
    private string $developmentOpportunities;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $targetCompletionDate;

    #[ORM\Column(type: 'text')]
    private string $successMeasure;

    #[ORM\Column(type: 'boolean')]
    private bool $isCompleted = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $completionNotes = null;
}
```

#### 9. Approval Entity (Polymorphic)
```php
#[ORM\Entity]
#[ORM\Table(name: 'approvals')]
class Approval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ApprovalType::class)]
    private ApprovalType $approverType;  // SUPERVISOR, HR_MANAGER

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $approver;

    #[ORM\Column(enumType: ApprovalStatus::class)]
    private ApprovalStatus $status = ApprovalStatus::PENDING;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $actionDate = null;

    // Polymorphic relations
    #[ORM\ManyToOne(targetEntity: PerformancePlan::class, inversedBy: 'approvals')]
    private ?PerformancePlan $plan = null;

    #[ORM\ManyToOne(targetEntity: Appraisal::class, inversedBy: 'approvals')]
    private ?Appraisal $appraisal = null;

    #[ORM\ManyToOne(targetEntity: PerformanceImprovementPlan::class, inversedBy: 'approvals')]
    private ?PerformanceImprovementPlan $pip = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
```

#### 10. CounsellingSession Entity (New - Supporting Continuous PM)
```php
#[ORM\Entity]
#[ORM\Table(name: 'counselling_sessions')]
class CounsellingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $employee;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $counsellor;  // Usually supervisor

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $sessionDate;

    #[ORM\Column(enumType: CounsellingType::class)]
    private CounsellingType $type;  // COACHING, FEEDBACK, CORRECTIVE, WARNING

    #[ORM\Column(type: 'text')]
    private string $issueDescription;  // Behaviors/facts discussed

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $rootCauseAnalysis = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $employeePerspective = null;

    #[ORM\Column(type: 'text')]
    private string $agreedActions;  // SMART improvement goals

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $supportToProvide = null;  // Training, tools, resources

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $followUpDate = null;

    #[ORM\Column(type: 'boolean')]
    private bool $employeeAcknowledged = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $acknowledgedAt = null;

    #[ORM\ManyToOne(targetEntity: Appraisal::class)]
    private ?Appraisal $relatedAppraisal = null;

    #[ORM\ManyToOne(targetEntity: PerformanceImprovementPlan::class)]
    private ?PerformanceImprovementPlan $resultingPip = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
```

#### 11. CheckIn Entity (New - Supporting Continuous Monitoring)
```php
#[ORM\Entity]
#[ORM\Table(name: 'check_ins')]
class CheckIn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $employee;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $supervisor;

    #[ORM\ManyToOne(targetEntity: PerformancePlan::class)]
    private ?PerformancePlan $performancePlan = null;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $checkInDate;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $progressNotes = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $challengesFaced = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $supportNeeded = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $supervisorFeedback = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $nextCheckInDate = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}
```

#### 12. 360 Survey Entities
```php
#[ORM\Entity]
#[ORM\Table(name: 'surveys')]
class Survey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $subjectEmployee;  // Who is being evaluated

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\Column(enumType: SurveyStatus::class)]
    private SurveyStatus $status = SurveyStatus::DRAFT;

    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyResponse::class)]
    private Collection $responses;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dueDate;
}

#[ORM\Entity]
#[ORM\Table(name: 'survey_responses')]
class SurveyResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Survey::class, inversedBy: 'responses')]
    private Survey $survey;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $respondent;

    #[ORM\Column(enumType: EvaluatorRelation::class)]
    private EvaluatorRelation $relation;  // SELF, PEER, SUPERVISOR, SUBORDINATE

    #[ORM\Column(type: 'json')]
    private array $answers = [];

    #[ORM\Column(type: 'boolean')]
    private bool $isAnonymous = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isCompleted = false;
}
```

### Enums

```php
enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}

enum PlanStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case SUPERVISOR_APPROVED = 'supervisor_approved';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

enum AppraisalType: string
{
    case MID_YEAR = 'mid_year';
    case ANNUAL = 'annual';
}

enum AppraisalStatus: string
{
    case CREATED = 'created';
    case EMPLOYEE_SUBMITTED = 'employee_submitted';
    case SUPERVISOR_REVIEWED = 'supervisor_reviewed';
    case APPROVED = 'approved';
}

enum PipStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case EXTENDED = 'extended';
}

enum ApprovalType: string
{
    case SUPERVISOR = 'supervisor';
    case HR_MANAGER = 'hr_manager';
}

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

enum KpiMode: string
{
    case NO_CASCADING = 'no_cascading';
    case CASCADING = 'cascading';
}

enum EvaluatorRelation: string
{
    case SELF = 'self';
    case PEER = 'peer';
    case SUPERVISOR = 'supervisor';
    case SUBORDINATE = 'subordinate';
}

enum SurveyStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case CLOSED = 'closed';
}

enum CounsellingType: string
{
    case COACHING = 'coaching';           // Regular developmental conversations
    case FEEDBACK = 'feedback';           // Performance feedback sessions
    case CORRECTIVE = 'corrective';       // Addressing specific issues
    case WARNING = 'warning';             // Formal warning discussion
}
```

---

## Module Breakdown

### 1. Dashboard Module

**Features:**
- Organization overview statistics
- Performance cycle status summary
- Pending approvals widget
- Recent activities feed
- Quick action buttons

**Controllers:**
- `DashboardController::index()` - Main dashboard view
- `DashboardController::getStats()` - AJAX stats refresh

**Services:**
- `DashboardStatisticsService` - Aggregate statistics calculations

### 2. Annual KPI/KRA Module

**Features:**
- Create/Edit/View performance plans
- Four-part objective structure
- Weight validation (must total 100%)
- Approval workflow
- Document generation (PDF)
- Version history

**Controllers:**
- `KpiController::index()` - List user's KPIs
- `KpiController::all()` - Admin view of all KPIs
- `KpiController::create()` - New KPI form
- `KpiController::show()` - View KPI detail
- `KpiController::edit()` - Edit KPI
- `KpiController::submit()` - Submit for approval
- `KpiController::approve()` - Approval action
- `KpiController::export()` - PDF/Excel export

**Services:**
- `PerformancePlanService` - CRUD operations
- `DocumentNumberGenerator` - Auto-generate document numbers
- `WeightValidationService` - Validate weights total 100%
- `PdfGeneratorService` - Generate PDF documents

### 3. Appraisal Module (Mid-Year & Annual)

**Features:**
- Create appraisal from performance plan
- Employee self-rating
- Supervisor rating
- Auto-calculate scores based on weights
- Rating scale reference
- Comments system
- Training completion tracking
- Career goal achievement tracking

**Controllers:**
- `AppraisalController::index()` - List appraisals
- `AppraisalController::create()` - Start new appraisal
- `AppraisalController::show()` - View appraisal
- `AppraisalController::rate()` - Rating interface
- `AppraisalController::review()` - Supervisor review
- `AppraisalController::finalize()` - Complete appraisal

**Services:**
- `AppraisalService` - Core appraisal logic
- `RatingCalculatorService` - Calculate weighted scores
- `OverallAssessmentService` - Derive final ratings

### 4. 360 Survey Module

**Features:**
- Create evaluations with multiple respondents
- Anonymous/named responses
- Multiple evaluator types
- Aggregated feedback view
- Comparison charts

**Controllers:**
- `SurveyController::create()` - Create new survey
- `SurveyController::respond()` - Submit response
- `SurveyController::results()` - View aggregated results

### 5. PIP Module

**Features:**
- Create improvement plans
- Structured improvement areas
- Progress tracking
- HR approval workflow
- Completion verification

**Controllers:**
- `PipController::index()` - List PIPs
- `PipController::create()` - New PIP
- `PipController::show()` - View PIP
- `PipController::review()` - HR review
- `PipController::update()` - Update progress

### 6. Continuous Monitoring Module (New - Supporting PM Cycle)

**Purpose:** Support the ongoing nature of performance management, not just annual reviews.

**Features:**
- Regular check-in scheduling and tracking
- Progress notes between formal reviews
- Counselling session documentation
- One-on-one meeting records
- Challenge tracking and support requests
- Escalation to PIP when needed

**Controllers:**
- `CheckInController::index()` - List check-ins for an employee
- `CheckInController::create()` - Schedule/record new check-in
- `CheckInController::show()` - View check-in details
- `CounsellingController::index()` - List counselling sessions
- `CounsellingController::create()` - Record counselling session
- `CounsellingController::show()` - View session with acknowledgement
- `CounsellingController::acknowledge()` - Employee acknowledges session

**Services:**
- `CheckInService` - Manage check-in CRUD and reminders
- `CounsellingService` - Document counselling with escalation logic
- `ReminderService` - Schedule follow-up notifications

**Dashboard Integration:**
- Upcoming check-ins widget
- Overdue check-ins alerts
- Recent counselling sessions for supervisors
- "Needs attention" employee list

### 7. Admin Module

**Features:**
- User management
- Department/Sub-department management
- System settings
- Rating scale configuration
- Report generation
- Audit logs

---

## Workflow Definitions

### Performance Plan Approval Workflow

```yaml
# config/packages/workflow.yaml
framework:
    workflows:
        performance_plan_approval:
            type: state_machine
            audit_trail:
                enabled: true
            marking_store:
                type: method
                property: status
            supports:
                - App\Entity\PerformancePlan
            initial_marking: draft
            places:
                - draft
                - submitted
                - supervisor_approved
                - approved
                - rejected
            transitions:
                submit:
                    from: draft
                    to: submitted
                supervisor_approve:
                    from: submitted
                    to: supervisor_approved
                hr_approve:
                    from: supervisor_approved
                    to: approved
                reject:
                    from: [submitted, supervisor_approved]
                    to: rejected
                revise:
                    from: rejected
                    to: draft
```

### Appraisal Workflow

```yaml
        appraisal_workflow:
            type: state_machine
            audit_trail:
                enabled: true
            marking_store:
                type: method
                property: status
            supports:
                - App\Entity\Appraisal
            initial_marking: created
            places:
                - created
                - employee_submitted
                - supervisor_reviewed
                - approved
            transitions:
                employee_submit:
                    from: created
                    to: employee_submitted
                supervisor_review:
                    from: employee_submitted
                    to: supervisor_reviewed
                finalize:
                    from: supervisor_reviewed
                    to: approved
                return_to_employee:
                    from: [employee_submitted, supervisor_reviewed]
                    to: created
```

### PIP Workflow

```yaml
        pip_workflow:
            type: state_machine
            audit_trail:
                enabled: true
            marking_store:
                type: method
                property: status
            supports:
                - App\Entity\PerformanceImprovementPlan
            initial_marking: draft
            places:
                - draft
                - submitted
                - accepted
                - in_progress
                - completed
                - extended
            transitions:
                submit:
                    from: draft
                    to: submitted
                accept:
                    from: submitted
                    to: accepted
                start:
                    from: accepted
                    to: in_progress
                complete:
                    from: in_progress
                    to: completed
                extend:
                    from: in_progress
                    to: extended
                resume:
                    from: extended
                    to: in_progress
```

---

## API Design

### RESTful Endpoints (Optional - for integrations)

```
# Authentication
POST   /api/login                    # Get JWT token
POST   /api/token/refresh            # Refresh token

# Users
GET    /api/users                    # List users
GET    /api/users/{id}               # Get user
GET    /api/users/{id}/subordinates  # Get user's subordinates

# Performance Plans
GET    /api/plans                    # List plans
POST   /api/plans                    # Create plan
GET    /api/plans/{id}               # Get plan
PUT    /api/plans/{id}               # Update plan
POST   /api/plans/{id}/submit        # Submit for approval
POST   /api/plans/{id}/approve       # Approve plan

# Appraisals
GET    /api/appraisals               # List appraisals
POST   /api/appraisals               # Create appraisal
GET    /api/appraisals/{id}          # Get appraisal
PUT    /api/appraisals/{id}/rate     # Submit ratings
POST   /api/appraisals/{id}/review   # Supervisor review

# PIPs
GET    /api/pips                     # List PIPs
POST   /api/pips                     # Create PIP
GET    /api/pips/{id}                # Get PIP
PUT    /api/pips/{id}                # Update PIP

# Dashboard
GET    /api/dashboard/stats          # Get dashboard statistics
```

---

## Security Considerations

### Authentication
- Symfony Security with form login
- Remember me functionality
- Password strength requirements
- Account lockout after failed attempts
- Two-factor authentication (optional)

### Authorization (Voters)

```php
// src/Security/Voter/PerformancePlanVoter.php
class PerformancePlanVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['VIEW', 'EDIT', 'SUBMIT', 'APPROVE'])
            && $subject instanceof PerformancePlan;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $plan = $subject;

        return match($attribute) {
            'VIEW' => $this->canView($plan, $user),
            'EDIT' => $this->canEdit($plan, $user),
            'SUBMIT' => $this->canSubmit($plan, $user),
            'APPROVE' => $this->canApprove($plan, $user),
            default => false,
        };
    }

    private function canView(PerformancePlan $plan, User $user): bool
    {
        // Owner, supervisor, or HR can view
        return $plan->getEmployee() === $user
            || $plan->getEmployee()->getSupervisor() === $user
            || in_array('ROLE_HR', $user->getRoles());
    }

    private function canEdit(PerformancePlan $plan, User $user): bool
    {
        // Only owner in draft status
        return $plan->getEmployee() === $user
            && $plan->getStatus() === PlanStatus::DRAFT;
    }

    private function canApprove(PerformancePlan $plan, User $user): bool
    {
        // Supervisor for first approval, HR for final
        if ($plan->getStatus() === PlanStatus::SUBMITTED) {
            return $plan->getEmployee()->getSupervisor() === $user;
        }
        if ($plan->getStatus() === PlanStatus::SUPERVISOR_APPROVED) {
            return in_array('ROLE_HR', $user->getRoles());
        }
        return false;
    }
}
```

### Role Hierarchy

```yaml
# config/packages/security.yaml
security:
    role_hierarchy:
        ROLE_HR: ROLE_USER
        ROLE_MANAGER: ROLE_USER
        ROLE_ADMIN: [ROLE_HR, ROLE_MANAGER]
        ROLE_SUPER_ADMIN: ROLE_ADMIN
```

### Audit Logging

```php
// src/EventSubscriber/AuditSubscriber.php
class AuditSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AuditLoggerService $auditLogger,
        private Security $security
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            WorkflowEvents::COMPLETED => 'onWorkflowCompleted',
        ];
    }

    public function onWorkflowCompleted(Event $event): void
    {
        $this->auditLogger->log(
            entity: $event->getSubject(),
            action: $event->getTransition()->getName(),
            user: $this->security->getUser(),
            metadata: $event->getContext()
        );
    }
}
```

---

## UI/UX Improvements

### Design Principles

1. **Consistent Layout** - Unified sidebar navigation, breadcrumbs
2. **Progressive Disclosure** - Show relevant information at each step
3. **Real-time Feedback** - Toast notifications, loading states
4. **Mobile Responsive** - Bootstrap 5 grid, touch-friendly controls
5. **Accessibility** - ARIA labels, keyboard navigation, contrast ratios

### Key UI Components

#### 1. Enhanced Data Tables
- Server-side pagination
- Column sorting and filtering
- Bulk actions
- Export options (Copy, Excel, PDF, Print)
- Column visibility toggle
- Saved filters/views

#### 2. Approval Status Badges
```html
<span class="badge bg-warning">Pending SUP</span>
<span class="badge bg-info">Pending HRM</span>
<span class="badge bg-success">Approved</span>
<span class="badge bg-danger">Rejected</span>
```

#### 3. Progress Indicators
- Stepper for multi-part forms
- Progress bars for appraisal completion
- Visual timeline for approval history

#### 4. Rating Input Component
```javascript
// Stimulus controller for rating input
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["input", "stars"]
    static values = { max: Number }

    rate(event) {
        const value = event.currentTarget.dataset.value
        this.inputTarget.value = value
        this.updateStars(value)
    }

    updateStars(value) {
        this.starsTargets.forEach((star, index) => {
            star.classList.toggle('active', index < value)
        })
    }
}
```

#### 5. Form Improvements
- Auto-save drafts
- Inline validation
- Weight percentage calculator
- Date range pickers
- Rich text editor for comments

### Page Layouts

#### Dashboard
```
┌─────────────────────────────────────────────────────────────┐
│  [♥]  Pulse                               [User ▼] [Notif] │
├──────────┬──────────────────────────────────────────────────┤
│          │  Welcome, [Name]                                 │
│ Dashboard│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐           │
│          │  │  53  │ │  41  │ │  12  │ │   3  │           │
│ KPIs     │  │Staff │ │ Male │ │Female│ │Pend. │           │
│  My KPIs │  └──────┘ └──────┘ └──────┘ └──────┘           │
│  All KPIs│                                                  │
│          │  ┌─────────────────┐  ┌─────────────────┐       │
│ Mid-Year │  │ Dept Breakdown  │  │ Recent Activity │       │
│          │  │ [Chart]         │  │ - Item 1        │       │
│ Annual   │  │                 │  │ - Item 2        │       │
│          │  └─────────────────┘  └─────────────────┘       │
│ 360      │                                                  │
│          │  ┌─────────────────────────────────────┐        │
│ PIP      │  │ Pending Approvals                   │        │
│          │  │ [Table of pending items]            │        │
│ Admin    │  └─────────────────────────────────────┘        │
└──────────┴──────────────────────────────────────────────────┘
```

#### KPI Form (4-Part Stepper)
```
┌─────────────────────────────────────────────────────────────┐
│  Create Annual KPIs                               [< Back]  │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ●───────────○───────────○───────────○                     │
│  Part I     Part II    Part III   Part IV                  │
│  Standards  Competency Training   Career                   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ Part I: Performance Standards                       │   │
│  │                                                     │   │
│  │ Objective 1: [________________________]            │   │
│  │ Weight: [10%]  Target Date: [2025-12-31]          │   │
│  │                                                     │   │
│  │ Activities:                                         │   │
│  │ 1.1 [Activity description] [Measures] [10%] [Date]│   │
│  │ 1.2 [Activity description] [Measures] [5%]  [Date]│   │
│  │ [+ Add Activity]                                   │   │
│  │                                                     │   │
│  │ [+ Add Objective]                                  │   │
│  │                                                     │   │
│  │ Total Weight: 15% / 100%                           │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  [Save Draft]                              [Next: Part II] │
└─────────────────────────────────────────────────────────────┘
```

---

## Implementation Roadmap

### Phase 1: Foundation (Weeks 1-2)
- [ ] Symfony project setup with required bundles
- [ ] Database migrations for core entities
- [ ] User authentication and basic authorization
- [ ] Base layout templates (Twig)
- [ ] Department/SubDepartment CRUD

### Phase 2: Core KPI Module (Weeks 3-4)
- [ ] PerformancePlan entity and repository
- [ ] Objective and KeyActivity management
- [ ] KPI form with 4-part structure
- [ ] Weight validation service
- [ ] Document number generation
- [ ] Basic list views with DataTables

### Phase 3: Approval Workflow (Week 5)
- [ ] Symfony Workflow configuration
- [ ] Approval entity and service
- [ ] Security voters for authorization
- [ ] Email notifications (Symfony Mailer)
- [ ] Approval UI components

### Phase 4: Appraisal Module (Weeks 6-7)
- [ ] Appraisal entity structure
- [ ] Rating components (ObjectiveRating, CompetencyRating)
- [ ] Score calculation service
- [ ] Mid-year and Annual review forms
- [ ] Training and Career review tracking

### Phase 5: Supporting Modules (Week 8)
- [ ] 360 Survey module
- [ ] PIP module
- [ ] Dashboard statistics
- [ ] PDF export functionality

### Phase 6: Polish & Testing (Weeks 9-10)
- [ ] UI/UX refinements
- [ ] Unit and integration tests
- [ ] Performance optimization
- [ ] Security audit
- [ ] Documentation

### Phase 7: Deployment (Week 11)
- [ ] Production environment setup
- [ ] Data migration from old system
- [ ] User training
- [ ] Go-live support

---

## Appendix

### Rating Scale Reference

| Rating | Score Range | Description | Action |
|--------|-------------|-------------|--------|
| 4 | 85% and above | As Expected - Meet job standards and requirements | Recognition/Promotion consideration |
| 3 | 65% - 84% | As Expected - Meet job standards and requirements | Continue development |
| 2 | 40% - 64% | Below expectation - Does not meet job requirements | Performance Improvement Plan needed |
| 1 | 39% and below | Poor Performance - Fails to meet job requirements consistently | Consider termination |

### Document Number Formats

| Document Type | Format | Example |
|---------------|--------|---------|
| Annual Performance Objectives | APO/{YEAR}/{SEQUENCE} | APO/2025/25 |
| Mid-Year Appraisal | APOHY/{YEAR}/{SEQUENCE} | APOHY/2026/2 |
| Annual Appraisal | APOA/{YEAR}/{SEQUENCE} | APOA/2026/1 |
| Performance Improvement Plan | HR-PIP/{YEAR}/{SEQUENCE} | HR-PIP/2024/22 |

### Environment Configuration

```env
# .env.local
APP_ENV=dev
APP_SECRET=your-secret-here
DATABASE_URL="postgresql://user:pass@127.0.0.1:5432/pms_hr?serverVersion=15"

# Mailer
MAILER_DSN=smtp://localhost:1025

# Redis Cache (optional)
REDIS_URL=redis://localhost:6379

# PDF Generation
WKHTMLTOPDF_PATH=/usr/local/bin/wkhtmltopdf
```

---

### Key Takeaways from PM Training

The system embodies these core principles from Honeyguide's performance management training:

1. **Performance management is continuous** - not just an annual event
2. **Focus on results** - outcomes matter more than activities
3. **Use SMART goals** - make expectations clear and measurable
4. **Give and receive feedback regularly** - don't wait for formal reviews
5. **Counselling is supportive** - it's about helping, not punishing
6. **Document everything** - good records protect everyone
7. **Communication is key** - engage in ongoing dialogue with supervisors

---

*Document Version: 1.1*
*Created: 2026-01-16*
*Updated: 2026-01-16*
*Author: Claude (AI Assistant)*

**References:**
- PMS HR App Screenshots (Current System Analysis)
- PM Training Staff Handout - Honeyguide Foundation (January 2026)
