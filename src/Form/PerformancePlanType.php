<?php

namespace App\Form;

use App\Entity\PerformancePlan;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformancePlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Add employee selector for HR/Admin users
        if ($options['show_employee_selector']) {
            $builder->add('employee', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $user) => $user->getFullName() . ' (' . $user->getEmail() . ')',
                'label' => 'Employee',
                'placeholder' => 'Select an employee...',
                'attr' => ['class' => 'form-select'],
                'query_builder' => fn(UserRepository $repo) => $repo->createQueryBuilder('u')
                    ->where('u.isActive = :active')
                    ->setParameter('active', true)
                    ->orderBy('u.lastName', 'ASC')
                    ->addOrderBy('u.firstName', 'ASC'),
            ]);
        }

        $builder
            ->add('year', IntegerType::class, [
                'label' => 'Plan Year',
                'attr' => [
                    'min' => 2020,
                    'max' => 2100,
                    'class' => 'form-control',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Plan Title',
                'attr' => [
                    'placeholder' => 'e.g., Annual Performance Goals 2024',
                    'class' => 'form-control',
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'help' => 'Performance period start date',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'help' => 'Performance period end date',
            ])
            ->add('performanceStandards', TextareaType::class, [
                'label' => 'Part 1: Performance Standards',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Describe the performance standards expected...',
                    'class' => 'form-control',
                ],
                'help' => 'Define the performance standards and expectations for this period.',
            ])
            ->add('competencies', TextareaType::class, [
                'label' => 'Part 2: Competencies',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'List key competencies to develop or demonstrate...',
                    'class' => 'form-control',
                ],
                'help' => 'Identify competencies required for effective job performance.',
            ])
            ->add('trainingGoals', TextareaType::class, [
                'label' => 'Part 3: Training Goals',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Describe training and development objectives...',
                    'class' => 'form-control',
                ],
                'help' => 'Outline training needs and development activities planned.',
            ])
            ->add('careerDevelopment', TextareaType::class, [
                'label' => 'Part 4: Career Development',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Describe career development goals and aspirations...',
                    'class' => 'form-control',
                ],
                'help' => 'Define career goals and progression plans.',
            ])
            ->add('objectives', CollectionType::class, [
                'entry_type' => ObjectiveType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'attr' => [
                    'data-controller' => 'form-collection',
                    'data-form-collection-item-class' => 'objective-item',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PerformancePlan::class,
            'show_employee_selector' => false,
        ]);

        $resolver->setAllowedTypes('show_employee_selector', 'bool');
    }
}
