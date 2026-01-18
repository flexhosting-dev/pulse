<?php

namespace App\Security\Voter;

use App\Entity\PerformancePlan;
use App\Entity\User;
use App\Enum\PlanStatus;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PerformancePlanVoter extends Voter
{
    public const CREATE = 'PLAN_CREATE';
    public const VIEW = 'PLAN_VIEW';
    public const EDIT = 'PLAN_EDIT';
    public const DELETE = 'PLAN_DELETE';
    public const SUBMIT = 'PLAN_SUBMIT';
    public const SUPERVISOR_APPROVE = 'PLAN_SUPERVISOR_APPROVE';
    public const HR_APPROVE = 'PLAN_HR_APPROVE';
    public const REJECT = 'PLAN_REJECT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // CREATE doesn't require a subject
        if ($attribute === self::CREATE) {
            return $subject === null || $subject instanceof User;
        }

        return in_array($attribute, [
                self::VIEW,
                self::EDIT,
                self::DELETE,
                self::SUBMIT,
                self::SUPERVISOR_APPROVE,
                self::HR_APPROVE,
                self::REJECT,
            ])
            && $subject instanceof PerformancePlan;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // Handle CREATE separately since it may have a target user instead of a plan
        if ($attribute === self::CREATE) {
            $targetUser = $subject instanceof User ? $subject : null;
            return $this->canCreate($user, $targetUser);
        }

        /** @var PerformancePlan $plan */
        $plan = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($plan, $user),
            self::EDIT => $this->canEdit($plan, $user),
            self::DELETE => $this->canDelete($plan, $user),
            self::SUBMIT => $this->canSubmit($plan, $user),
            self::SUPERVISOR_APPROVE => $this->canSupervisorApprove($plan, $user),
            self::HR_APPROVE => $this->canHrApprove($plan, $user),
            self::REJECT => $this->canReject($plan, $user),
            default => false,
        };
    }

    private function canView(PerformancePlan $plan, User $user): bool
    {
        // Owner can always view
        if ($plan->getEmployee() === $user) {
            return true;
        }

        // Supervisor of the owner can view
        if ($plan->getEmployee()?->getSupervisor() === $user) {
            return true;
        }

        // HR and Admin can view all
        if ($this->hasRole($user, 'ROLE_HR') || $this->hasRole($user, 'ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function canEdit(PerformancePlan $plan, User $user): bool
    {
        // Only the owner can edit, and only in draft or rejected status
        if ($plan->getEmployee() !== $user) {
            return false;
        }

        return $plan->getStatus()->isEditable();
    }

    private function canDelete(PerformancePlan $plan, User $user): bool
    {
        // Only the owner can delete, and only in draft status
        if ($plan->getEmployee() !== $user) {
            return false;
        }

        return $plan->getStatus() === PlanStatus::DRAFT;
    }

    private function canSubmit(PerformancePlan $plan, User $user): bool
    {
        // Only the owner can submit
        if ($plan->getEmployee() !== $user) {
            return false;
        }

        // Must be in draft or rejected status
        if (!$plan->getStatus()->canSubmit()) {
            return false;
        }

        // Weights must be valid (total 100%)
        return $plan->isWeightValid();
    }

    private function canSupervisorApprove(PerformancePlan $plan, User $user): bool
    {
        // Must be the supervisor of the owner
        if ($plan->getEmployee()?->getSupervisor() !== $user) {
            return false;
        }

        return $plan->getStatus()->canSupervisorApprove();
    }

    private function canHrApprove(PerformancePlan $plan, User $user): bool
    {
        // Must have HR role
        if (!$this->hasRole($user, 'ROLE_HR') && !$this->hasRole($user, 'ROLE_ADMIN')) {
            return false;
        }

        return $plan->getStatus()->canHrApprove();
    }

    private function canReject(PerformancePlan $plan, User $user): bool
    {
        if (!$plan->getStatus()->canReject()) {
            return false;
        }

        // Supervisor can reject when status is submitted
        if ($plan->getStatus() === PlanStatus::SUBMITTED) {
            return $plan->getEmployee()?->getSupervisor() === $user;
        }

        // HR can reject when status is supervisor_approved
        if ($plan->getStatus() === PlanStatus::SUPERVISOR_APPROVED) {
            return $this->hasRole($user, 'ROLE_HR') || $this->hasRole($user, 'ROLE_ADMIN');
        }

        return false;
    }

    private function canCreate(User $currentUser, ?User $targetUser): bool
    {
        // If no target user specified, allow (creating for self)
        if ($targetUser === null) {
            return true;
        }

        // Creating for self is always allowed
        if ($targetUser === $currentUser) {
            return true;
        }

        // HR and Admin can create plans for any employee
        if ($this->hasRole($currentUser, 'ROLE_HR') || $this->hasRole($currentUser, 'ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function hasRole(User $user, string $role): bool
    {
        return in_array($role, $user->getRoles());
    }
}
