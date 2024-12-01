<?php

declare(strict_types=1);

namespace App\Service;

namespace App\Service;

use Doctrine\DBAL\Connection;

class Universal
{
    private array $countries = [
        'name' => 'countries_name'
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataStatistic(array $columns, $groups): array
    {
        return [];
//        $queryBuilder = $this->connection->createQueryBuilder()
//            ->select($columns)
//            ->from()
//
//        $this->getFrom($groups)
    }
}