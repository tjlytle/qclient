<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClient\Collection;

use GuzzleHttp\Psr7\Request;

class DeviceDefinition extends AbstractCollection implements \Iterator
{
    use NonePagingTrait;

    protected function fetchPage()
    {
        $request = new Request('GET', 'device_definitions');
        $response = $this->client->send($request);
        $response->getBody()->rewind();

        if (!$json = json_decode($response->getBody()->getContents(), true)) {
        }

        return $json;
    }

    protected function hydrateResource($data)
    {
        $definition = new \QClient\Resource\DeviceDefinition();
        $definition->hydrate($data);
        return $definition;
    }
}
