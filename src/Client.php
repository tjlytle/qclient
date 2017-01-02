<?php
namespace QClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use QClient\Collection\Device;
use QClient\Collection\DeviceDefinition;
use QClient\Collection\Signal;

/**
 * Q Cloud Client
 *
 * @property DeviceDefinition device_definitions The device definition collection.
 * @property Device devices The registered devices collection.
 * @property Signal signals The signals collection.
 */
class Client
{
    /**
     * Bearer Token
     * @var string
     */
    protected $token;

    protected $host;

    protected $base;

    protected $client;

    public function __construct($token, $host = 'q.daskeyboard.com', $base = '/api/1.0/')
    {
        $this->token = $token;
        $this->host  = $host;
        $this->base  = $base;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        $uri = $request->getUri();
        $uri = $uri->withHost($this->host)->withPath($this->base . $uri->getPath())->withScheme('http');
        $request = $request->withUri($uri);
        $request = $request->withHeader('Authorization', 'Bearer ' . $this->token);

        return $this->getHttpClient()->send($request);
    }

    public function __get($name)
    {
        switch ($name) {
            case 'device_definitions':
                return new DeviceDefinition($this);
            case 'devices':
                return new Device($this);
            case 'signals':
                return new Signal($this);
        }

        throw new \RuntimeException('invalid collection: ' . $name);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (!isset($this->client)) {
            $this->setHttpClient(new \GuzzleHttp\Client());
        }

        return $this->client;
    }

    public function setHttpClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }
}
