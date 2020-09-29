<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617192710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Roulette rounds';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("create table rounds
            (
                id         bigserial               not null
                    constraint rounds_pk
                        primary key,
                state      integer,
                finished   boolean   default false not null,
                created_at timestamp default now() not null,
                updated_at timestamp default now() not null
            )");

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE rounds');
    }
}
