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
            $result[] = $this->preparingDataForStorage($item, $layers);
        }
    }

    private function preparingDataForStorage(array $data, array $layers)
    {
        $result = [];
        foreach ($data as $key => $item) {
            $tableData = explode('__', $key);
            $result[$layers[array_search($tableData[0], $layers)]][$tableData[1]] = $item;
        }

        return array_keys($result);
    }

    private function saveBatch(array $data): void
    {
        $pipeline = $this->redisClient->multi(Redis::PIPELINE);

        foreach ($data as $id => $value) {
            $key = static::NAME_DB . ':' . static::USER_ID . ':' . $value['id'] . ':' . $value;
            $pipeline->set($key, json_encode($value));
        }

        $pipeline->exec();
    }
}