<?php
namespace QClient\Collection;

trait NonePagingTrait
{
    protected $index = 0;

    protected $page;

    abstract protected function fetchPage();
    abstract protected function hydrateResource($data, $index);

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->page) && isset($this->page[$this->index]);
    }

    public function rewind()
    {
        $this->page = $this->fetchPage();
    }

    public function current()
    {
        return $this->hydrateResource($this->page[$this->index]);
    }
}
