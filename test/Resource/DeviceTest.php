<?php
namespace QClientTest\Resource;

use QClient\Resource\Device;
use QClientTest\ResponseFixture;

class DeviceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Device $resource
     * @param $json
     * @dataProvider getResources
     */
    public function testHasArrayAccess(Device $resource, $json)
    {
        foreach ($json as $property => $data) {
            $this->assertSame($data, $resource[$property]);
        }
    }

    public function getResources()
    {
        $fixture = new ResponseFixture();
        $data = $fixture->getResponseJson('devices.json');

        foreach ($data as $device) {
            $resource = new Device();
            $resource->hydrate($device);
            yield [$resource, $device];
        }
    }
}
