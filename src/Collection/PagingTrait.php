<?php
namespace QClient\Collection;

trait PagingTrait
{
    protected $index = 0;

    protected $page;

    abstract protected function fetchPage($page);
    abstract protected function hydrateResource($data, $index);
    abstract protected function hasNextPage($data);
    abstract protected function getResourceProperty();

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return ($this->page['page'] * $this->page['size']) + $this->index;
    }

    public function valid()
    {
        if (isset($this->page) && isset($this->page[$this->getResourceProperty()][$this->index])) {
            return true;
        }

        if ($this->hasNextPage($this->page)) {
            $this->index = 0;
            $page = $this->page['page'];
            $this->page = $this->fetchPage($page + 1);
            return true;
        }

        return false;
    }

    public function rewind()
    {
        $this->page = $this->fetchPage();
    }

    public function current()
    {
        return $this->hydrateResource($this->page[$this->getResourceProperty()][$this->index]);
    }
}
