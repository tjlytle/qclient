<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClient\Resource;

/**
 * Class Signal
 * @method Signal setName(string $name)
 * @method Signal setPid(string $pid)
 * @method Signal setZone(string $zone)
 * @method Signal setColor(string $color)
 * @method Signal setMessage(string $message)
 * @method Signal setEffect(string $effect)
 * @method Signal setNotify(bool $notify)
 * @method Signal setRead(bool $read)
 * @method Signal setArchived(bool $archived)
 * @method Signal setMuted(bool $muted)
 */
class Signal extends AbstractResource
{
    protected $write = [];

    protected function getSettableProperties()
    {
        return [
            'Name' => 'name',
            'Pid' => 'pid',
            'Zone' => 'zoneId',
            'Color' => 'color',
            'Message' => 'message',
            'Effect' => 'effect',
            'Notify' => 'shouldNotify',
            'Read' => 'isRead',
            'Archived' => 'isArchived',
            'Muted' => 'isMuted'
        ];
    }

    public function __call($name, $arguments)
    {
        if (!strpos($name, 'set') === 0) {
            throw new \RuntimeException('invalid method: ' . $name);
        }

        $map = $this->getSettableProperties();
        $prop = substr($name, 3);

        if (!isset($map[$prop])) {
            throw new \RuntimeException('invalid property: ' . $prop);
        }

        //can do any data manipulation here

        $this->write[$map[$prop]] = $arguments[0];
        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), $this->write);
    }
}
