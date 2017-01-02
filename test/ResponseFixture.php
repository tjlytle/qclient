<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClientTest;

use GuzzleHttp\Psr7\Response;

class ResponseFixture
{
    public function getResponse($name, $status = 200)
    {
        if (!file_exists(__DIR__ . '/responses/' . $name)) {
            throw new \RuntimeException('can not find response: ' . $name);
        }

        $response = new Response($status);
        $response->getBody()->write(file_get_contents(__DIR__ . '/responses/' . $name));
        $response->getBody()->rewind();
        return $response;
    }

    public function getResponseJson($name)
    {
        if (!file_exists(__DIR__ . '/responses/' . $name)) {
            throw new \RuntimeException('can not find response: ' . $name);
        }

        return json_decode(file_get_contents(__DIR__ . '/responses/' . $name), true);
    }
}
