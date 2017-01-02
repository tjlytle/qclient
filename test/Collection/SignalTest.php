<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClientTest\Collection;

use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use QClient\Collection\Signal;
use QClientTest\ResponseFixture;

class SignalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    protected $client;

    /**
     * @var Signal
     */
    protected $collection;

    /**
     * @var ResponseFixture;
     */
    protected $responseFixture;

    public function setUp()
    {
        $this->client = $this->prophesize('QClient\Client');
        $this->collection = new Signal($this->client->reveal());

        $this->responseFixture = new ResponseFixture();
    }

    public function testIterationPages()
    {
        $test = $this;

        $this->client->send(Argument::that(function (RequestInterface $request) {
            $this->assertSame('GET', $request->getMethod());
            $this->assertSame('signals', $request->getUri()->getPath());
            return true;
        }))->will(function ($args) use ($test) {
            $request = $args[0];
            $query = [];
            parse_str($request->getUri()->getQuery(), $query);
            $test->assertArrayHasKey('page', $query);

            return $test->responseFixture->getResponse('signals_' . $query['page'] . '.json');
        })->should(function ($calls) use ($test) {
            $test->assertCount(3, $calls);

            $page = 0;
            /* @var $call \Prophecy\Call\Call */
            foreach ($calls as $call) {
                $request = $call->getArguments()[0];

                $query = [];
                parse_str($request->getUri()->getQuery(), $query);
                $test->assertArrayHasKey('page', $query);
                $test->assertEquals($page, $query['page']);

                $page++;
            }
        });

        iterator_to_array($this->collection);
    }

    public function testItemsAreResources()
    {
        $this->client->send(Argument::any())
            ->willReturn($this->responseFixture->getResponse('signals_2.json'));

        foreach ($this->collection as $item) {
            $this->assertInstanceOf('QClient\Resource\Signal', $item);
        }
    }

    public function testResourcesMatchResponse()
    {
        $response = $this->responseFixture->getResponse('signals_2.json');
        $data = json_decode($response->getBody()->getContents(), true);
        $response->getBody()->rewind();

        $this->client->send(Argument::any())
            ->willReturn($response);

        foreach ($this->collection as $key => $item) {
            $expected = array_pop($data['content']);
            $this->assertSame($expected, $item->jsonSerialize());
        }
    }

    public function testCanPostResource()
    {
        $signal = new \QClient\Resource\Signal();
        $signal->setName('test')
               ->setPid('DK5QPID')
               ->setZone('KEY_A')
               ->setColor('#008000');

        $this->client->send(Argument::that(function (RequestInterface $request) use ($signal) {
            $this->assertSame('POST', $request->getMethod());
            $this->assertSame('signals', $request->getUri()->getPath());
            $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));

            $request->getBody()->rewind();
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $this->assertSame($signal->jsonSerialize(), $data);
            return true;
        }))->willReturn($this->responseFixture->getResponse('signal.json'));

        $this->collection->post($signal);

        $this->assertEquals('92233', $signal['id']);
    }

    public function testCanPatchResource()
    {
        $signal = new \QClient\Resource\Signal();
        $json = $this->responseFixture->getResponseJson('signal.json');
        $signal->hydrate($json);

        $signal->setRead(true);
        $signal->setArchived(true);
        $signal->setMuted(true);

        $this->client->send(Argument::that(function (RequestInterface $request) use ($signal) {
            $this->assertSame('PATCH', $request->getMethod());
            $this->assertSame('signals/' . $signal['id'] . '/status', $request->getUri()->getPath());
            $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));

            $request->getBody()->rewind();
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $this->assertSame($signal->jsonSerialize(), $data);
            return true;
        }))->willReturn($this->responseFixture->getResponse('signal.json'));

        $this->collection->patch($signal);

        //ensure API response sets data, even if that's not what we wrote
        $this->assertEquals(false, $signal['isRead']);
    }
}
