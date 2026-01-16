<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Create Departments
        $departments = [];
        $deptData = [
            ['name' => 'Conservation', 'code' => 'CONS'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Administration', 'code' => 'ADMIN'],
            ['name' => 'Programs', 'code' => 'PROG'],
            ['name' => 'Human Resources', 'code' => 'HR'],
        ];

        foreach ($deptData as $data) {
            $dept = new Department();
            $dept->setName($data['name']);
            $dept->setCode($data['code']);
            $manager->persist($dept);
            $departments[$data['code']] = $dept;
        }

        // Create Admin User
        $admin = new User();
        $admin->setEmail('admin@pulse.local');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setEmployeeNumber('EMP001');
        $admin->setPosition('System Administrator');
        $admin->setGender('male');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setDepartment($departments['ADMIN']);
        $admin->setHireDate(new \DateTime('2020-01-15'));
        $manager->persist($admin);

        // Create HR Manager
        $hrManager = new User();
        $hrManager->setEmail('hr@pulse.local');
        $hrManager->setFirstName('Sarah');
        $hrManager->setLastName('Johnson');
        $hrManager->setEmployeeNumber('EMP002');
        $hrManager->setPosition('HR Manager');
        $hrManager->setGender('female');
        $hrManager->setRoles(['ROLE_HR']);
        $hrManager->setPassword($this->passwordHasher->hashPassword($hrManager, 'hr123'));
        $hrManager->setDepartment($departments['HR']);
        $hrManager->setHireDate(new \DateTime('2019-03-01'));
        $manager->persist($hrManager);

        // Create Manager
        $deptManager = new User();
        $deptManager->setEmail('manager@pulse.local');
        $deptManager->setFirstName('James');
        $deptManager->setLastName('Wilson');
        $deptManager->setEmployeeNumber('EMP003');
        $deptManager->setPosition('Conservation Director');
        $deptManager->setGender('male');
        $deptManager->setRoles(['ROLE_MANAGER']);
        $deptManager->setPassword($this->passwordHasher->hashPassword($deptManager, 'manager123'));
        $deptManager->setDepartment($departments['CONS']);
        $deptManager->setHireDate(new \DateTime('2018-06-15'));
        $manager->persist($deptManager);

        // Update Conservation department head
        $departments['CONS']->setHead($deptManager);

        // Create Regular Staff Members
        $staffData = [
            ['email' => 'john.doe@pulse.local', 'firstName' => 'John', 'lastName' => 'Doe', 'gender' => 'male', 'dept' => 'CONS', 'position' => 'Field Officer'],
            ['email' => 'jane.smith@pulse.local', 'firstName' => 'Jane', 'lastName' => 'Smith', 'gender' => 'female', 'dept' => 'CONS', 'position' => 'Research Associate'],
            ['email' => 'bob.brown@pulse.local', 'firstName' => 'Bob', 'lastName' => 'Brown', 'gender' => 'male', 'dept' => 'FIN', 'position' => 'Accountant'],
            ['email' => 'alice.green@pulse.local', 'firstName' => 'Alice', 'lastName' => 'Green', 'gender' => 'female', 'dept' => 'PROG', 'position' => 'Program Officer'],
            ['email' => 'mike.taylor@pulse.local', 'firstName' => 'Mike', 'lastName' => 'Taylor', 'gender' => 'male', 'dept' => 'ADMIN', 'position' => 'Office Assistant'],
        ];

        $empNum = 4;
        foreach ($staffData as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmployeeNumber(sprintf('EMP%03d', $empNum));
            $user->setPosition($data['position']);
            $user->setGender($data['gender']);
            $user->setRoles([]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setDepartment($departments[$data['dept']]);
            $user->setSupervisor($data['dept'] === 'CONS' ? $deptManager : null);
            $user->setHireDate(new \DateTime(sprintf('2022-%02d-01', $empNum)));
            $manager->persist($user);
            $empNum++;
        }

        $manager->flush();
    }
}
