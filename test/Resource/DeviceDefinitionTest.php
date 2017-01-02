<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClientTest\Resource;

use QClient\Resource\DeviceDefinition;
use QClientTest\ResponseFixture;

class DeviceDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param DeviceDefinition $resource
     * @param $json
     * @dataProvider getResources
     */
    public function testHasArrayAccess(DeviceDefinition $resource, $json)
    {
        foreach ($json as $property => $data) {
            $this->assertSame($data, $resource[$property]);
        }
    }

    public function getResources()
    {
        $fixture = new ResponseFixture();
        $data = $fixture->getResponseJson('device_definitions.json');

        foreach ($data as $device) {
            $resource = new DeviceDefinition();
            $resource->hydrate($device);
            yield [$resource, $device];
        }
    }
}
