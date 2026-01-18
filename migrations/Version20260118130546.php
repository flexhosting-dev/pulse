<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118130546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE performance_plans ADD COLUMN start_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE performance_plans ADD COLUMN end_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__performance_plans AS SELECT id, document_number, year, title, performance_standards, competencies, training_goals, career_development, status, supervisor_approved_at, hr_approved_at, rejection_reason, rejected_at, submitted_at, created_at, updated_at, employee_id, supervisor_approved_by_id, hr_approved_by_id, rejected_by_id FROM performance_plans');
        $this->addSql('DROP TABLE performance_plans');
        $this->addSql('CREATE TABLE performance_plans (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, document_number VARCHAR(50) DEFAULT NULL, year INTEGER NOT NULL, title VARCHAR(255) NOT NULL, performance_standards CLOB DEFAULT NULL, competencies CLOB DEFAULT NULL, training_goals CLOB DEFAULT NULL, career_development CLOB DEFAULT NULL, status VARCHAR(20) NOT NULL, supervisor_approved_at DATETIME DEFAULT NULL, hr_approved_at DATETIME DEFAULT NULL, rejection_reason CLOB DEFAULT NULL, rejected_at DATETIME DEFAULT NULL, submitted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, employee_id INTEGER NOT NULL, supervisor_approved_by_id INTEGER DEFAULT NULL, hr_approved_by_id INTEGER DEFAULT NULL, rejected_by_id INTEGER DEFAULT NULL, CONSTRAINT FK_6DC72F4E8C03F15C FOREIGN KEY (employee_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DC72F4E15900F00 FOREIGN KEY (supervisor_approved_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DC72F4E22556E55 FOREIGN KEY (hr_approved_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DC72F4ECBF05FC9 FOREIGN KEY (rejected_by_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO performance_plans (id, document_number, year, title, performance_standards, competencies, training_goals, career_development, status, supervisor_approved_at, hr_approved_at, rejection_reason, rejected_at, submitted_at, created_at, updated_at, employee_id, supervisor_approved_by_id, hr_approved_by_id, rejected_by_id) SELECT id, document_number, year, title, performance_standards, competencies, training_goals, career_development, status, supervisor_approved_at, hr_approved_at, rejection_reason, rejected_at, submitted_at, created_at, updated_at, employee_id, supervisor_approved_by_id, hr_approved_by_id, rejected_by_id FROM __temp__performance_plans');
        $this->addSql('DROP TABLE __temp__performance_plans');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC72F4E28F2AE32 ON performance_plans (document_number)');
        $this->addSql('CREATE INDEX IDX_6DC72F4E8C03F15C ON performance_plans (employee_id)');
        $this->addSql('CREATE INDEX IDX_6DC72F4E15900F00 ON performance_plans (supervisor_approved_by_id)');
        $this->addSql('CREATE INDEX IDX_6DC72F4E22556E55 ON performance_plans (hr_approved_by_id)');
        $this->addSql('CREATE INDEX IDX_6DC72F4ECBF05FC9 ON performance_plans (rejected_by_id)');
    }
}
