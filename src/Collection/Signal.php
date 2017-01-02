<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClient\Collection;

use GuzzleHttp\Psr7\Request;

class Signal extends AbstractCollection implements \Iterator
{
    use PagingTrait;

    public function post(\QClient\Resource\Signal $signal)
    {
        $request = new Request('POST', 'signals', [
            'Content-Type' => 'application/json'
        ]);
        $request->getBody()->write(json_encode($signal));

        $response = $this->client->send($request);
        $response->getBody()->rewind();

        if (!$json = json_decode($response->getBody()->getContents(), true)) {
        }

        $signal->hydrate($json);
    }

    public function patch(\QClient\Resource\Signal $signal)
    {
        $request = new Request('PATCH', 'signals/' . $signal['id'] . '/status', [
            'Content-Type' => 'application/json'
        ]);
        $request->getBody()->write(json_encode($signal));

        $response = $this->client->send($request);
        $response->getBody()->rewind();

        if (!$json = json_decode($response->getBody()->getContents(), true)) {
        }

        $signal->hydrate($json);
    }

    protected function fetchPage($page = 0)
    {
        $request = new Request('GET', 'signals?page=' . (int) $page);
        $response = $this->client->send($request);
        $response->getBody()->rewind();

        if (!$json = json_decode($response->getBody()->getContents(), true)) {
        }

        return $json;
    }

    protected function hydrateResource($data)
    {
        $definition = new \QClient\Resource\Signal();
        $definition->hydrate($data);
        return $definition;
    }

    protected function hasNextPage($data)
    {
        return isset($data['hasNextPage']) && $data['hasNextPage'];
    }

    protected function getResourceProperty()
    {
        return 'content';
    }
}
