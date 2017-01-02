<?php
namespace QClientTest;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use QClient\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $http;

    protected $client;

    protected $request;

    protected $token = 'test123';
    protected $host = 'example.com';
    protected $base = '/path/to/api/';
    protected $path = 'testPath';

    public function setUp()
    {
        $this->http = $this->prophesize('GuzzleHttp\Client');
        $this->client = new Client($this->token, $this->host, $this->base);
        $this->client->setHttpClient($this->http->reveal());
        $this->request = new Request('GET', $this->path);
    }

    public function testSendAddsAuth()
    {
        $this->http->send(Argument::that(function (RequestInterface $request) {
            $this->assertSame('Bearer ' . $this->token, $request->getHeaderLine('Authorization'));
            return true;
        }))->shouldBeCalled();

        $this->client->send($this->request);
    }

    public function testSendAddsHost()
    {
        $this->http->send(Argument::that(function (RequestInterface $request) {
            $this->assertSame($this->host, $request->getUri()->getHost());
            $this->assertSame('http', $request->getUri()->getScheme());
            return true;
        }))->shouldBeCalled();

        $this->client->send($this->request);
    }

    public function testSendAddsBase()
    {
        $this->http->send(Argument::that(function (RequestInterface $request) {
            $this->assertSame($this->base . $this->path, $request->getUri()->getPath());
            return true;
        }))->shouldBeCalled();

        $this->client->send($this->request);
    }

    public function testReturnsResponse()
    {
        $response = new Response();

        $this->http->send(Argument::any())->willReturn($response);

        $this->assertSame($response, $this->client->send($this->request));
    }
}
