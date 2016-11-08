<?php
namespace AppBundle\Collection;

use ArrayIterator;

/**
 * Class Collection
 *
 * This class holds the collection of objects
 *
 * @package AppBundle\Collection
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Collection items
     *
     * @var array
     */
    protected $items = [];

    /**
     * Adds new value to collection
     *
     * @param  mixed $value
     */
    public function put($value)
    {
        $this->set(null, $value);
    }

    /**
     * Adds new value to collection with key
     *
     * @param  string $key
     * @param  mixed $value
     */
    public function set($key, $value)
    {
        if ($key === null) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Checks if key exists in the collection
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get collection item
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->items[$key];
    }

    /**
     * Remove collection item
     *
     * @param  string $key
     */
    public function remove($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Get all items in collection
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Remove all items from the collection
     */
    public function removeAll()
    {
        $this->items = [];
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
