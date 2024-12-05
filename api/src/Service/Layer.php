<?php

declare(strict_types=1);

namespace App\Service;

use Generator;
use Iterator;
use Redis;

readonly class Layer
{
    private const string NAME_DB = 'roi';

    private const string USER_ID = 'sergey';

    public function __construct(
        private Redis $redisClient,
    ) {
    }

    public function save(Iterator $data, array $layers)
    {
        $result = [];
        foreach ($data as $item) {
            $result = $this->groupDataByLayers($item, $layers);
        }
        echo '<pre>';
        var_dump(iterator_to_array($result));die;
//        foreach ($layers as $layer) {
//
//        }
    }

    private function groupDataByLayers(array $data, array $layers)
    {
        $result = [];
        foreach ($data as $key => $item) {
            $tableData = explode('__', $key);
            $result[$layers[array_search($tableData[0], $layers)]][$tableData[1]] = $item;
        }

        var_dump($result);die;
    }
}