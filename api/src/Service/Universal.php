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
        self::GROUP_COUNTRY => self::GROUP_COUNTRY,
        self::GROUP_CITY => self::GROUP_CITY,
        self::GROUP_USERS => self::GROUP_USERS,
        self::GROUP_COMPANIES => self::GROUP_COMPANIES,
        self::GROUP_SCHOOLS => self::GROUP_SCHOOLS,
        self::GROUP_MUSEUMS => self::GROUP_MUSEUMS,
        self::GROUP_CINEMAS => self::GROUP_CINEMAS,
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
            'countryCode' => 'country.Code as country__code',
            'countryName' => 'country.Name as country__name',
            'countryRegion' => 'country.Region as country__region',
            'countryContinent' => 'country.Continent as country__continent',
        ],
        self::GROUP_CITY  => [
            'cityName' => 'city.Name as city__name',
            'cityCountryCode' => 'city.CountryCode as city__country_code',
            'cityPopulation' => 'city.Population as city__population',
        ],
        self::GROUP_USERS => [
            'usersFirstName' => 'users.first_name as users__first_name',
            'usersLastName' => 'users.last_name as users__last_name',
            'usersCountryId' => 'users.country_id as users__country_id',
            'usersCityId' => 'users.city_id as users__city_id',
            'usersGender' => 'users.gender as users__gender',
            'usersAge' => 'users.age as users__age',
            'usersCompanyId' => 'users.company_id as users__company_id',
        ],
        self::GROUP_COMPANIES => [
            'companiesName' => 'companies.name as companies__name',
            'companiesCountryId' => 'companies.country_id as companies__country_id',
            'companiesCityId' => 'companies.city_id as companies__city_id',
        ],
        self::GROUP_SCHOOLS => [
            'schoolsName' => 'schools.name as schools__name',
            'schoolsCountryId' => 'schools.country_id as schools__country_id',
            'schoolsCityId' => 'schools.city_id as schools__city_id',
        ],
        self::GROUP_MUSEUMS => [
            'museumsName' => 'museums.name as museums__name',
            'museumsCountryId' => 'museums.country_id as museums__country_id',
            'museumsCityId' => 'museums.city_id as museums__city_id',
        ],
        self::GROUP_CINEMAS => [
            'cinemasName' => 'cinemas.Name as cinemas__name',
            'cinemasCountryId' => 'cinemas.country_id as cinemas__country_id',
            'cinemasCityId' => 'cinemas.city_id as cinemas__city_id',
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
        $queryBuilder = $this->setJoins($queryBuilder, $groups);
        $queryBuilder = $this->setSelect($queryBuilder, $groups, $columns);

        var_dump($queryBuilder->getSQL());die;

//        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }

    private function setFrom(
        QueryBuilder $queryBuilder,
        array $groups,
    ): QueryBuilder {
        $firstGroup = static::GROUPS[$groups[0]] ?? null;

        if (empty($firstGroup)) {
            throw new Exception('Group not found');
        }

        return $queryBuilder->from($firstGroup, $firstGroup);
    }

    private function setJoins(QueryBuilder $queryBuilder, array $groups): QueryBuilder
    {
        if (!empty($groups)) {
            foreach ($groups as $key => $group) {
                if ($key === 0) {
                    continue;
                }

                $firstTable = $groups[$key-1];
//var_dump($firstTable . '.id = ' . $group . '.' . $firstTable . '_id');die;
                $queryBuilder->innerJoin($firstTable, $group, $group, $firstTable . '.id = ' . $group . '.' . $firstTable . '_id');
            }
        }

        return $queryBuilder;
    }

    private function setSelect(
        QueryBuilder $queryBuilder,
        array $groups,
        array $columns
    ): QueryBuilder
    {
        $fields = '';
        if (!empty($columns) && !empty($groups)) {
            foreach ($groups as $group) {
                $fields .= static::GROUPS[$group] . '.id AS ' . $group . '__id, ';
                foreach ($columns as $column) {
                    if (empty(static::FIELDS[$group][$column])) {
                        continue;
                    }
                    $fields .= static::FIELDS[$group][$column] . ', ';
                }
            }
            $queryBuilder->select(rtrim($fields, ', '));
        } else {
            $queryBuilder->select('*');
        }

        return $queryBuilder;
    }
}