<?php

namespace App\Entity;

use App\Enum\PlanStatus;
use App\Repository\PerformancePlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PerformancePlanRepository::class)]
#[ORM\Table(name: 'performance_plans')]
#[ORM\HasLifecycleCallbacks]
class PerformancePlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true, nullable: true)]
    private ?string $documentNumber = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 2020, max: 2100)]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $performanceStandards = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $competencies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $trainingGoals = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $careerDevelopment = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(length: 20, enumType: PlanStatus::class)]
    private PlanStatus $status = PlanStatus::DRAFT;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $employee = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $supervisorApprovedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $supervisorApprovedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $hrApprovedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $hrApprovedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rejectionReason = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $rejectedBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $rejectedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $submittedAt = null;

    /** @var Collection<int, Objective> */
    #[ORM\OneToMany(mappedBy: 'performancePlan', targetEntity: Objective::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $objectives;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->objectives = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->year = (int) date('Y');
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(?string $documentNumber): static
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getPerformanceStandards(): ?string
    {
        return $this->performanceStandards;
    }

    public function setPerformanceStandards(?string $performanceStandards): static
    {
        $this->performanceStandards = $performanceStandards;
        return $this;
    }

    public function getCompetencies(): ?string
    {
        return $this->competencies;
    }

    public function setCompetencies(?string $competencies): static
    {
        $this->competencies = $competencies;
        return $this;
    }

    public function getTrainingGoals(): ?string
    {
        return $this->trainingGoals;
    }

    public function setTrainingGoals(?string $trainingGoals): static
    {
        $this->trainingGoals = $trainingGoals;
        return $this;
    }

    public function getCareerDevelopment(): ?string
    {
        return $this->careerDevelopment;
    }

    public function setCareerDevelopment(?string $careerDevelopment): static
    {
        $this->careerDevelopment = $careerDevelopment;
        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->startDate !== null && $this->endDate !== null) {
            if ($this->endDate <= $this->startDate) {
                $context->buildViolation('End date must be after start date.')
                    ->atPath('endDate')
                    ->addViolation();
            }
        }
    }

    public function getStatus(): PlanStatus
    {
        return $this->status;
    }

    public function setStatus(PlanStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;
        return $this;
    }

    public function getSupervisorApprovedBy(): ?User
    {
        return $this->supervisorApprovedBy;
    }

    public function setSupervisorApprovedBy(?User $supervisorApprovedBy): static
    {
        $this->supervisorApprovedBy = $supervisorApprovedBy;
        return $this;
    }

    public function getSupervisorApprovedAt(): ?\DateTimeImmutable
    {
        return $this->supervisorApprovedAt;
    }

    public function setSupervisorApprovedAt(?\DateTimeImmutable $supervisorApprovedAt): static
    {
        $this->supervisorApprovedAt = $supervisorApprovedAt;
        return $this;
    }

    public function getHrApprovedBy(): ?User
    {
        return $this->hrApprovedBy;
    }

    public function setHrApprovedBy(?User $hrApprovedBy): static
    {
        $this->hrApprovedBy = $hrApprovedBy;
        return $this;
    }

    public function getHrApprovedAt(): ?\DateTimeImmutable
    {
        return $this->hrApprovedAt;
    }

    public function setHrApprovedAt(?\DateTimeImmutable $hrApprovedAt): static
    {
        $this->hrApprovedAt = $hrApprovedAt;
        return $this;
    }

    public function getRejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function setRejectionReason(?string $rejectionReason): static
    {
        $this->rejectionReason = $rejectionReason;
        return $this;
    }

    public function getRejectedBy(): ?User
    {
        return $this->rejectedBy;
    }

    public function setRejectedBy(?User $rejectedBy): static
    {
        $this->rejectedBy = $rejectedBy;
        return $this;
    }

    public function getRejectedAt(): ?\DateTimeImmutable
    {
        return $this->rejectedAt;
    }

    public function setRejectedAt(?\DateTimeImmutable $rejectedAt): static
    {
        $this->rejectedAt = $rejectedAt;
        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?\DateTimeImmutable $submittedAt): static
    {
        $this->submittedAt = $submittedAt;
        return $this;
    }

    /**
     * @return Collection<int, Objective>
     */
    public function getObjectives(): Collection
    {
        return $this->objectives;
    }

    public function addObjective(Objective $objective): static
    {
        if (!$this->objectives->contains($objective)) {
            $this->objectives->add($objective);
            $objective->setPerformancePlan($this);
        }
        return $this;
    }

    public function removeObjective(Objective $objective): static
    {
        if ($this->objectives->removeElement($objective)) {
            if ($objective->getPerformancePlan() === $this) {
                $objective->setPerformancePlan(null);
            }
        }
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Calculate total weight of all objectives.
     * Must equal 100% for submission.
     */
    public function getTotalWeight(): int
    {
        $total = 0;
        foreach ($this->objectives as $objective) {
            $total += $objective->getWeight() ?? 0;
        }
        return $total;
    }

    public function isWeightValid(): bool
    {
        return $this->getTotalWeight() === 100;
    }

    public function __toString(): string
    {
        return $this->documentNumber ?? $this->title ?? 'Performance Plan';
    }
}
