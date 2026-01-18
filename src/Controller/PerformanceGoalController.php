<?php

namespace App\Controller;

use App\Entity\PerformancePlan;
use App\Entity\User;
use App\Enum\PlanStatus;
use App\Form\PerformancePlanType;
use App\Form\RejectionType;
use App\Repository\PerformancePlanRepository;
use App\Repository\UserRepository;
use App\Security\Voter\PerformancePlanVoter;
use App\Service\DocumentNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/goals')]
class PerformanceGoalController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PerformancePlanRepository $planRepository,
        private UserRepository $userRepository,
        private DocumentNumberGenerator $documentNumberGenerator,
        private WorkflowInterface $performancePlanStateMachine
    ) {
    }

    #[Route('', name: 'app_goals_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $plans = $this->planRepository->findByEmployee($user);

        return $this->render('goals/index.html.twig', [
            'plans' => $plans,
        ]);
    }

    #[Route('/all', name: 'app_goals_all', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function all(): Response
    {
        $user = $this->getUser();
        $plans = $this->planRepository->findAllAccessible($user);

        // Get pending counts for badges
        $pendingSupervisor = count($this->planRepository->findPendingSupervisorApproval($user));
        $pendingHr = 0;
        if (in_array('ROLE_HR', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $pendingHr = count($this->planRepository->findPendingHrApproval());
        }

        return $this->render('goals/all.html.twig', [
            'plans' => $plans,
            'pendingSupervisor' => $pendingSupervisor,
            'pendingHr' => $pendingHr,
        ]);
    }

    #[Route('/new', name: 'app_goals_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $isHrOrAdmin = $this->isGranted('ROLE_HR') || $this->isGranted('ROLE_ADMIN');

        $plan = new PerformancePlan();
        $targetEmployee = null;

        // Handle employee selection for HR/Admin
        if ($isHrOrAdmin) {
            // Check for employee query parameter
            $employeeId = $request->query->get('employee');
            if ($employeeId) {
                $targetEmployee = $this->userRepository->find($employeeId);
                if ($targetEmployee) {
                    $plan->setEmployee($targetEmployee);
                }
            }
        } else {
            // Regular users can only create for themselves
            $plan->setEmployee($currentUser);
        }

        // Check CREATE authorization (for the target employee if specified)
        $this->denyAccessUnlessGranted(PerformancePlanVoter::CREATE, $targetEmployee);

        $form = $this->createForm(PerformancePlanType::class, $plan, [
            'show_employee_selector' => $isHrOrAdmin,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // For regular users, ensure employee is set to current user
            if (!$isHrOrAdmin) {
                $plan->setEmployee($currentUser);
            }

            // Verify authorization for the selected employee
            $this->denyAccessUnlessGranted(PerformancePlanVoter::CREATE, $plan->getEmployee());

            $this->entityManager->persist($plan);
            $this->entityManager->flush();

            $this->addFlash('success', 'Performance plan created successfully.');

            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        return $this->render('goals/new.html.twig', [
            'plan' => $plan,
            'form' => $form,
            'isHrOrAdmin' => $isHrOrAdmin,
            'targetEmployee' => $targetEmployee,
        ]);
    }

    #[Route('/{id}', name: 'app_goals_show', methods: ['GET'])]
    public function show(PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::VIEW, $plan);

        $user = $this->getUser();

        return $this->render('goals/show.html.twig', [
            'plan' => $plan,
            'canEdit' => $this->isGranted(PerformancePlanVoter::EDIT, $plan),
            'canDelete' => $this->isGranted(PerformancePlanVoter::DELETE, $plan),
            'canSubmit' => $this->isGranted(PerformancePlanVoter::SUBMIT, $plan),
            'canSupervisorApprove' => $this->isGranted(PerformancePlanVoter::SUPERVISOR_APPROVE, $plan),
            'canHrApprove' => $this->isGranted(PerformancePlanVoter::HR_APPROVE, $plan),
            'canReject' => $this->isGranted(PerformancePlanVoter::REJECT, $plan),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_goals_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::EDIT, $plan);

        $form = $this->createForm(PerformancePlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Performance plan updated successfully.');

            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        return $this->render('goals/edit.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/submit', name: 'app_goals_submit', methods: ['POST'])]
    public function submit(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::SUBMIT, $plan);

        if (!$this->isCsrfTokenValid('submit' . $plan->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        if (!$plan->isWeightValid()) {
            $this->addFlash('error', 'Objective weights must total 100% before submission.');
            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        // Generate document number on first submission
        if (!$plan->getDocumentNumber()) {
            $documentNumber = $this->documentNumberGenerator->generatePerformancePlanNumber($plan->getYear());
            $plan->setDocumentNumber($documentNumber);
        }

        // Apply workflow transition
        if ($this->performancePlanStateMachine->can($plan, 'submit')) {
            $this->performancePlanStateMachine->apply($plan, 'submit');
            $plan->setSubmittedAt(new \DateTimeImmutable());
            // Clear any previous rejection data
            $plan->setRejectionReason(null);
            $plan->setRejectedBy(null);
            $plan->setRejectedAt(null);

            $this->entityManager->flush();

            $this->addFlash('success', 'Performance plan submitted for approval.');
        } else {
            $this->addFlash('error', 'Cannot submit plan in current status.');
        }

        return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
    }

    #[Route('/{id}/supervisor-approve', name: 'app_goals_supervisor_approve', methods: ['POST'])]
    public function supervisorApprove(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::SUPERVISOR_APPROVE, $plan);

        if (!$this->isCsrfTokenValid('supervisor_approve' . $plan->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        if ($this->performancePlanStateMachine->can($plan, 'supervisor_approve')) {
            $this->performancePlanStateMachine->apply($plan, 'supervisor_approve');
            $plan->setSupervisorApprovedBy($this->getUser());
            $plan->setSupervisorApprovedAt(new \DateTimeImmutable());

            $this->entityManager->flush();

            $this->addFlash('success', 'Performance plan approved. It has been forwarded to HR for final approval.');
        } else {
            $this->addFlash('error', 'Cannot approve plan in current status.');
        }

        return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
    }

    #[Route('/{id}/hr-approve', name: 'app_goals_hr_approve', methods: ['POST'])]
    public function hrApprove(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::HR_APPROVE, $plan);

        if (!$this->isCsrfTokenValid('hr_approve' . $plan->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        if ($this->performancePlanStateMachine->can($plan, 'hr_approve')) {
            $this->performancePlanStateMachine->apply($plan, 'hr_approve');
            $plan->setHrApprovedBy($this->getUser());
            $plan->setHrApprovedAt(new \DateTimeImmutable());

            $this->entityManager->flush();

            $this->addFlash('success', 'Performance plan has been fully approved.');
        } else {
            $this->addFlash('error', 'Cannot approve plan in current status.');
        }

        return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
    }

    #[Route('/{id}/reject', name: 'app_goals_reject', methods: ['GET', 'POST'])]
    public function reject(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::REJECT, $plan);

        $form = $this->createForm(RejectionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Determine which transition to use based on current status
            $transition = $plan->getStatus() === PlanStatus::SUBMITTED
                ? 'reject_by_supervisor'
                : 'reject_by_hr';

            if ($this->performancePlanStateMachine->can($plan, $transition)) {
                $this->performancePlanStateMachine->apply($plan, $transition);
                $plan->setRejectionReason($data['rejectionReason']);
                $plan->setRejectedBy($this->getUser());
                $plan->setRejectedAt(new \DateTimeImmutable());

                $this->entityManager->flush();

                $this->addFlash('success', 'Performance plan has been rejected. The employee will be notified.');

                return $this->redirectToRoute('app_goals_all');
            } else {
                $this->addFlash('error', 'Cannot reject plan in current status.');
            }
        }

        return $this->render('goals/reject.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_goals_delete', methods: ['POST'])]
    public function delete(Request $request, PerformancePlan $plan): Response
    {
        $this->denyAccessUnlessGranted(PerformancePlanVoter::DELETE, $plan);

        if (!$this->isCsrfTokenValid('delete' . $plan->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_goals_show', ['id' => $plan->getId()]);
        }

        $this->entityManager->remove($plan);
        $this->entityManager->flush();

        $this->addFlash('success', 'Performance plan deleted.');

        return $this->redirectToRoute('app_goals_index');
    }
}
