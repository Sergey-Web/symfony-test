<?php

declare(strict_types=1);

namespace App\Service;

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

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

    public function __construct(
        private readonly Connection $connection,
        private readonly Layer $layer,
    ){
    }

    /**
     * @throws Exception
     */
    public function getDataStatistic()
    {
        $batchSize = 1000;
        $lastId = 0;
        $cycle = 0;

        do {
            $queryBuilder = $this->connection->createQueryBuilder()
                ->from('users')
                ->select(
                    'users.id as users__id',
                    'users.first_name as users__first_name',
                    'users.last_name as users__last_name',
                    'users.country_id as users__country_id',
                    'users.city_id as users__city_id',
                    'users.gender as users__gender',
                    'users.age as users__age',
                    'users.company_id as users__company_id',
                    'country.id as country__id',
                    'country.Code as country__code',
                    'country.Name as country__name',
                    'country.Region as country__region',
                    'country.Continent as country__continent',
                    'city.id as city__id',
                    'city.Name as city__name',
                    'city.CountryCode as city__country_code',
                    'city.Population as city__population',
                )
                ->join('users', 'country', 'country', 'country.id = users.country_id')
                ->join('users', 'city', 'city', 'city.CountryCode = country.Code')
                ->where('users.id > ?')
                ->setParameter(0, $lastId)
                ->setMaxResults($batchSize);

            $result = $queryBuilder->executeQuery()->fetchAllAssociative();

            if (!empty($result)) {
                $lastId = end($result)['users__id'];
            }

            $cycle += $batchSize;

            $this->layer->save($result, ['users', 'country', 'city']);
        } while (count($result) > 0 && $cycle < 100000);
    }
}