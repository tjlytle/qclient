<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClientTest\Collection;

use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use QClient\Collection\Device;
use QClientTest\ResponseFixture;

class DeviceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    protected $client;

    /**
     * @var Device
     */
    protected $collection;

    /**
     * @var ResponseFixture;
     */
    protected $responseFixture;

    public function setUp()
    {
        $this->client = $this->prophesize('QClient\Client');
        $this->collection = new Device($this->client->reveal());

        $this->responseFixture = new ResponseFixture();
    }

    public function testIterationFetchesPage()
    {
        $this->client->send(Argument::that(function (RequestInterface $request) {
            $this->assertSame('GET', $request->getMethod());
            $this->assertSame('devices', $request->getUri()->getPath());
            return true;
        }))->willReturn($this->responseFixture->getResponse('devices.json'));

        iterator_to_array($this->collection);
    }

    public function testItemsAreDefinitions()
    {
        $this->client->send(Argument::any())
             ->willReturn($this->responseFixture->getResponse('devices.json'));

        foreach ($this->collection as $item) {
            $this->assertInstanceOf('QClient\Resource\Device', $item);
        }
    }

    public function testDefinitionsMatchResponse()
    {
        $response = $this->responseFixture->getResponse('devices.json');
        $data = json_decode($response->getBody()->getContents(), true);
        $response->getBody()->rewind();

        $this->client->send(Argument::any())
             ->willReturn($response);

        foreach ($this->collection as $key => $item) {
            $this->assertSame($data[$key], $item->jsonSerialize());
        }
    }
}
