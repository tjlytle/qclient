<?php
/**
 * @copyright Copyright (c) 2017. Timothy Lytle (tjlytle)
 * @license https://github.com/tjlytle/qclient/LICENSE.txt MIT License
 */

namespace QClient\Resource;

class AbstractResource implements \JsonSerializable, \ArrayAccess
{
    protected $data = [];

    public function hydrate($data)
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('can not write to read only object');
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('can not write to read only object');
    }
}
