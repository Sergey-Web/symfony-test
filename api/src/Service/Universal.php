<?php

declare(strict_types=1);

namespace App\Service;

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class Universal
{
    private const GROUPS = [
        self::GROUP_COUNTRY,
        self::GROUP_CITY,
        self::GROUP_USERS,
        self::GROUP_COMPANIES,
        self::GROUP_SCHOOLS,
        self::GROUP_MUSEUMS,
        self::GROUP_CINEMAS,
    ];

    private const GROUP_COUNTRY = 'country';
    private const GROUP_CITY = 'city';
    private const GROUP_USERS = 'users';
    private const GROUP_COMPANIES = 'companies';
    private const GROUP_SCHOOLS = 'schools';
    private const GROUP_MUSEUMS = 'museums';
    private const GROUP_CINEMAS = 'cinemas';

    private const FIELDS = [
        self::GROUP_COUNTRY  => [
            'code' => 'country.Code as country__code',
            'name' => 'country.Name as country__name',
            'region' => 'country.Region as country__region',
            'continent' => 'country.Continent as country__continent',
        ],
        self::GROUP_CITY  => [
            'name' => 'city.Name as city__name',
            'country_code' => 'city.CountryCode as city__country_code',
            'population' => 'city.Population as city__population',
        ],
        self::GROUP_USERS => [
            'first_name' => 'users.first_name as users__first_name',
            'last_name' => 'users.last_name as users__last_name',
            'country_id' => 'users.country_id as users__country_id',
            'city_id' => 'users.city_id as users__city_id',
            'gender' => 'users.gender as users__gender',
            'age' => 'users.age as users__age',
            'company_id' => 'users.company_id as users__company_id',
        ],
        self::GROUP_COMPANIES => [
            'name' => 'companies.name as companies__name',
            'country_id' => 'companies.country_id as companies__country_id',
            'city_id' => 'companies.city_id as companies__city_id',
        ],
        self::GROUP_SCHOOLS => [
            'name' => 'schools.name as schools__name',
            'country_id' => 'schools.country_id as schools__country_id',
            'city_id' => 'schools.city_id as schools__city_id',
        ],
        self::GROUP_MUSEUMS => [
            'name' => 'museums.name as museums__name',
            'country_id' => 'museums.country_id as museums__country_id',
            'city_id' => 'museums.city_id as museums__city_id',
        ],
        self::GROUP_CINEMAS => [
            'name' => 'cinemas.Name as cinemas__name',
            'country_id' => 'cinemas.country_id as cinemas__country_id',
            'city_id' => 'cinemas.city_id as cinemas__city_id',
        ]
    ];

    private array $countries = [
        'name' => 'countries_name'
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function getDataStatistic(array $columns, array $groups)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder = $this->setFrom($queryBuilder, $groups);
//        $queryBuilder = $this->setJoins($queryBuilder, $groups);
        $queryBuilder = $this->setSelect($queryBuilder, $groups, $columns);
        return $queryBuilder->executeQuery()->fetchAllAssociative();

        return [];
    }

    private function setFrom(
        QueryBuilder $queryBuilder,
        array $groups,
    ): QueryBuilder {
        $firstGroup = static::GROUPS[array_key_first($groups)] ?? null;

        if (empty($firstGroup)) {
            throw new Exception('Group not found');
        }

        return $queryBuilder->from($firstGroup, $firstGroup);
    }

//    private function setJoins(QueryBuilder $queryBuilder, array $groups): QueryBuilder
//    {
//        if (!empty($groups)) {
//            foreach ($groups as $group) {
//                $queryBuilder->join(, $group);
//            }
//        }
//    }

    private function setSelect(
        QueryBuilder $queryBuilder,
        array $groups,
        array $columns
    ): QueryBuilder
    {
        $fields = '';
        if (!empty($columns) && !empty($groups)) {
            foreach ($groups as $group) {
                foreach ($columns as $column) {
                    $fields .= static::GROUPS[$group][$column] . ' ';
                }
            }

            return $queryBuilder->select(rtrim($fields));
        } else {
            return $queryBuilder->select('*');
        }
    }
}