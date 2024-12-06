<?php

declare(strict_types=1);

namespace App\Service;

use Redis;

final class Layer
{
    private const string NAME_DB = 'roi';

    private const string USER_ID = 'sergey';

    private array $data = [];

    public function __construct(
        private Redis $redisClient,
    ) {
    }

    public function getRecord(string $userId, string $layer, string $id)
    {
        $pattern = Layer::NAME_DB . ":{$userId}:{$layer}:{$id}";
        $iterator = null;

        while (($keys = $this->redisClient->scan($iterator, $pattern)) !== false) {
            $values = $this->redisClient->mget($keys);

            foreach ($keys as $index => $key) {
                yield $key => $values[$index];
            }
        }
    }

    public function getLayer(string $userId, string $layer)
    {
        $pattern = Layer::NAME_DB . ":{$userId}:{$layer}:*";
        $iterator = null;

        while (($keys = $this->redisClient->scan($iterator, $pattern)) !== false) {
            $values = $this->redisClient->mget($keys);

            foreach ($keys as $index => $key) {
                yield $key => $values[$index];
            }
        }
    }

    public function save(array $data, array $layers)
    {
        foreach ($data as $item) {
            $groupLayers = $this->groupDataByLayers($item, $layers);
            $this->generateKeysWithData($groupLayers);

            if (count($this->data) >= 1000) {
                $this->saveBatch();
            }
        }

        $this->saveBatch();
    }

    private function groupDataByLayers(array $data, array $layers)
    {
        $groupData = [];
        foreach ($data as $key => $item) {
            $tableData = explode('__', $key);
            $groupData[$layers[array_search($tableData[0], $layers)]][$tableData[1]] = $item;
        }

        return array_values($groupData);
    }

    private function saveBatch(): void
    {
        $pipeline = $this->redisClient->multi(Redis::PIPELINE);

        foreach ($this->data as $key => $value) {
            $pipeline->set($key, json_encode($value));
        }

        $pipeline->exec();
        $this->data = [];
    }

    private function generateKeysWithData(array $data)
    {
        foreach ($data as $key => $value) {
            $id = $key !== 0 ? $data[$key-1]['id'] . '_' . $value['id'] : $value['id'];

            unset($value['id']);
            $this->data[static::NAME_DB . ':' . static::USER_ID . ':' . $key . ':' . $id] = $value;
        }
    }
}