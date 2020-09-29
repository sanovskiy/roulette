<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617192720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'users';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("create table users
            (
                id         bigserial               not null
                    constraint users_pk
                        primary key,
                username      varchar(180) unique,
                roles   json,
                password varchar(1023)
            )");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE users');
    }
}
