<?php
namespace QClientTest\Resource;

use QClient\Resource\Signal;
use QClientTest\ResponseFixture;

class SignalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Signal $resource
     * @param $json
     * @dataProvider getResources
     */
    public function testHasArrayAccess(Signal $resource, $json)
    {
        foreach ($json as $property => $data) {
            $this->assertSame($data, $resource[$property]);
        }
    }

    public function getResources()
    {
        $fixture = new ResponseFixture();
        $data = $fixture->getResponseJson('signals_1.json');

        foreach ($data['content'] as $signal) {
            $resource = new Signal();
            $resource->hydrate($signal);
            yield [$resource, $signal];
        }
    }

    /**
     * @param $method
     * @param $value
     * @param $key
     * @param $expected
     * @dataProvider getProperties
     */
    public function testCanSetProperties($method, $value, $key, $expected)
    {
        $signal = new Signal();
        $signal->$method($value);

        $data = $signal->jsonSerialize();
        $this->assertSame($expected, $data[$key]);
    }

    public function getProperties()
    {
        return [
            ['setName', 'Test Signal', 'name', 'Test Signal'],
            ['setPid', 'DK5QPID', 'pid', 'DK5QPID'],
            ['setZone', 'KEY_A', 'zoneId', 'KEY_A'],
            ['setColor', '#008000', 'color', '#008000'],

            ['setMessage', 'Test Message', 'message', 'Test Message'],
            ['setEffect', 'BLINK', 'effect', 'BLINK'],
            ['setNotify', true, 'shouldNotify', true],
            ['setRead', true, 'isRead', true],
            ['setArchived', true, 'isArchived', true],
            ['setMuted', true, 'isMuted', true],
        ];
    }
}
