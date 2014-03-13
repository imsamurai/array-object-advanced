<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Oct 30, 2013
 * Time: 5:21:33 PM
 */

/**
 * Advanced array object
 *  
 * @package ArrayObjectA
 * @subpackage Utility
 */
class ArrayObjectA extends ArrayObject implements JsonSerializable {

	/**
	 * Merge arrays
	 * 
	 * @param ArrayObjectA $Array
	 * @return \ArrayObjectA
	 */
	public function merge(ArrayObjectA $Array) {
		$this->exchangeArray(array_merge($this->getArrayCopy(), $Array->getArrayCopy()));
		return $this;
	}

	/**
	 * Apply callback to each element of array
	 * 
	 * @param callable $callback
	 * @return \ArrayObjectA
	 */
	public function map(callable $callback) {
		$that = clone $this;
		foreach ($that->getIterator() as $index => $item) {
			$that->offsetSet($index, $callback($item));
		}
		return $that;
	}

	/**
	 * Remove all elements that not satisfy $callback
	 * 
	 * @param callable $callback
	 * @param bool $resetKeys
	 * @return \ArrayObjectA
	 */
	public function filter(callable $callback, $resetKeys = true) {
		$array = array_filter($this->getArrayCopy(), $callback);
		return new ArrayObjectA($resetKeys ? array_values($array) : $array);
	}

	/**
	 * Reduces array into single value using $callback
	 * 
	 * @param callable $callback
	 * @param mixed $init Initial value
	 * @return mixed
	 */
	public function reduce(callable $callback, $init = null) {
		return array_reduce(iterator_to_array($this->getIterator()), $callback, $init);
	}

	/**
	 * Group elements of array
	 * 
	 * @param callable $callback
	 * @return \ArrayObjectA
	 */
	public function group(callable $callback) {
		$array = array();
		foreach ($this->getIterator() as $index => $item) {
			$group = $callback($item);
			$array[$group][] = $item;
		}
		return new ArrayObjectA($array);
	}

	/**
	 * For json serialization
	 * 
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->getArrayCopy();
	}

	/**
	 * Make unique
	 * 
	 * @param callable $callback Groupping callback
	 * @return \ArrayObjectA
	 */
	public function unique(callable $callback) {
		$that = $this
				->group($callback)
				->map(function($group) {
					return $group[0];
				});
		return $that->resetKeys();
	}

	/**
	 * Reset array keys to integer from 0 to n
	 * 
	 * @return ArrayObjectA
	 */
	public function resetKeys() {
		return new ArrayObjectA(array_values($this->getArrayCopy()));
	}

}
