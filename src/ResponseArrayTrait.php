<?php

namespace Waller\Gold;

trait ResponseArrayTrait
{
    /**
     * Gets data by using key with dotted notation.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function get($key = null)
    {
        $key = $this->constructKey($key);

        if (is_null($key)) {
            return $this->result();
        }

        return $this->parseKey($key, function ($part, $result) {
            if (isset($result[$part])) {
                return $result[$part];
            }
        });
    }

    /**
     * Checks if key exists.
     *
     * @param string|null $key
     *
     * @return bool
     */
    public function exists($key = null)
    {
        $key = $this->constructKey($key);

        return $this->parseKey($key, function ($part, $result) {
            return array_key_exists($part, $result);
        });
    }

    /**
     * Checks if key exists and not null.
     *
     * @param string|null $key
     *
     * @return bool
     */
    public function has($key = null)
    {
        $key = $this->constructKey($key);

        return $this->parseKey($key, function ($part, $result) {
            return isset($result[$part]);
        });
    }

    /**
     * Gets first element.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function first($key = null)
    {
        $value = $this->get($key);

        if (is_array($value)) {
            return reset($value);
        }

        return $value;
    }

    /**
     * Gets last element.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function last($key = null)
    {
        $value = $this->get($key);

        if (is_array($value)) {
            return end($value);
        }

        return $value;
    }

    /**
     * Checks if response contains value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->result());
    }

    /**
     * Sets current key.
     *
     * @param string|null $key
     *
     * @return static
     */
    public function key($key = null)
    {
        $new = clone $this;
        $new->current = $key;

        return $new;
    }

    /**
     * Gets response keys.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->result());
    }

    /**
     * Gets response values.
     *
     * @return array
     */
    public function values()
    {
        return array_values($this->result());
    }

    /**
     * Gets random value.
     *
     * @param int         $number
     * @param string|null $key
     *
     * @return mixed
     */
    public function random($number = 1, $key = null)
    {
        $value = $this->get($key);

        if (is_array($value)) {
            $keys = array_keys($value);
            $keysLength = count($keys);

            shuffle($keys);

            if ($number > $keysLength) {
                $number = $keysLength;
            }

            for ($result = [], $count = 0; $count < $number; $count++) {
                $result[$keys[$count]] = $value[$keys[$count]];
            }

            return count($result) > 1 ? $result : current($result);
        }

        return $value;
    }

    /**
     * Counts response items.
     *
     * @param string|null $key
     *
     * @return int
     */
    public function count($key = null)
    {
        if (is_null($this->constructKey($key))) {
            return count($this->result());
        }

        $value = $this->get($key);

        if (is_array($value)) {
            return count($value);
        }

        return (int) $value > 0;
    }

    /**
     * Flattens multi-dimensional array.
     *
     * @param string|null $key
     *
     * @return array
     */
    public function flatten($key = null)
    {
        $array = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator((array) $this->get($key))
        );

        $tmp = [];
        foreach ($array as $value) {
            $tmp[] = $value;
        }

        return $tmp;
    }

    /**
     * Gets sum of values.
     *
     * @param string|null $key
     *
     * @return float
     */
    public function sum($key = null)
    {
        return array_sum($this->flatten($key));
    }

    /**
     * Gets response item by key.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function __invoke($key = null)
    {
        return $this->key($key);
    }

    /**
     * Constructs full key.
     *
     * @param string|null $key
     *
     * @return string|null
     */
    protected function constructKey($key = null)
    {
        if (!is_null($key) && !is_null($this->current)) {
            return $this->current.'.'.$key;
        }

        if (is_null($key) && !is_null($this->current)) {
            return $this->current;
        }

        return $key;
    }

    /**
     * Parses dotted notation.
     *
     * @param string     $key
     * @param callable   $callback
     * @param array|null $result
     *
     * @return mixed
     */
    protected function parseKey($key, callable $callback, $result = null)
    {
        $parts = is_array($key) ? $key : explode('.', trim($key, '.'));
        $result = $result ?: $this->result();

        while (!is_null($part = array_shift($parts))) {
            if ($part == '*') {
                array_walk($result, function (&$value) use ($parts, $callback) {
                    $value = $this->parseKey($parts, $callback, $value);
                });

                return $result;
            }

            if (!$return = $callback($part, $result)) {
                return $return;
            }

            $result = $result[$part];
        }

        return $return;
    }
}
