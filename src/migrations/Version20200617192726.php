<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617192726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'logs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("create table logs
            (
                id bigserial not null constraint logs_pk primary key,
                user_id bigint not null references users,
                round_id bigint not null references rounds,
                data jsonb,
                created_at timestamp not null 
            )");

        $this->addSql("create index logs_round_id_index on logs (round_id);");

        $this->addSql("create index logs_user_id_round_id_index on logs (user_id, round_id);");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE logs');
    }
}
