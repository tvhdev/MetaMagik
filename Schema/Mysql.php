<?php

namespace Kanboard\Plugin\MetaMagik\Schema;

use PDO;

const VERSION = 4;

function version_4(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types CHANGE COLUMN `options` `options` VARCHAR(10000);');
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN column_number INTEGER DEFAULT 1');
}

function version_3(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN position INTEGER DEFAULT 1');
    // Migrate all metadata_types position
    $position = 1;
    $urq = $pdo->prepare('UPDATE metadata_types SET position=? WHERE id=?');
    $rq = $pdo->prepare('SELECT * FROM metadata_types ORDER BY id ASC');
    $rq->execute();
    foreach ($rq->fetchAll(PDO::FETCH_ASSOC) as $metadata_types) {
        $urq->execute(array($position, $metadata_types['id']));
        $position++;
    }
}
function version_2(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN options VARCHAR(255)');
}

function version_1(PDO $pdo)
{
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS metadata_types (
          id INT PRIMARY KEY AUTO_INCREMENT,
          human_name VARCHAR(255) NOT NULL,
          machine_name VARCHAR(255) NOT NULL,
          data_type VARCHAR(50) NOT NULL,
          is_required TINYINT(1) DEFAULT 0,
          attached_to VARCHAR(50) NOT NULL,
          UNIQUE(machine_name, attached_to)
        ) ENGINE=InnoDB CHARSET=utf8
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS metadata_has_type (
          id INT PRIMARY KEY AUTO_INCREMENT,
          type_id INT NOT NULL,
          metadata_id INT NOT NULL,
          FOREIGN KEY(type_id) REFERENCES metadata_types(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ');
}
