<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClient\Collection;

use GuzzleHttp\Psr7\Request;

class Device extends AbstractCollection implements \Iterator
{
    use NonePagingTrait;

    protected function fetchPage()
    {
        $request = new Request('GET', 'devices');
        $response = $this->client->send($request);
        $response->getBody()->rewind();

        if (!$json = json_decode($response->getBody()->getContents(), true)) {
        }

        return $json;
    }

    protected function hydrateResource($data)
    {
        $definition = new \QClient\Resource\Device();
        $definition->hydrate($data);
        return $definition;
    }
}
