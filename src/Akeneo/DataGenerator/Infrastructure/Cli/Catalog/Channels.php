<?php

namespace Akeneo\DataGenerator\Infrastructure\Cli\Catalog;

class Channels implements \Iterator, \Countable
{
    private $index;
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): Channel
    {
        return $this->items[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->items[$this->index]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }
}
