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
class ArrayObjectA extends ArrayObject {

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
		foreach ($this->getIterator() as $index => $item) {
			$this->offsetSet($index, $callback($item));
		}
		return $this;
	}

	/**
	 * Remove all elements that not satisfy $callback
	 * 
	 * @param callable $callback
	 * @param bool $resetKeys
	 * @return \ArrayObjectA
	 */
	public function filter(callable $callback, $resetKeys = true) {
		$Iterator = new CallbackFilterIterator($this->getIterator(), $callback);
		return new ArrayObjectA(iterator_to_array($Iterator, !$resetKeys));
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
		$this->exchangeArray($array);
		return $this;
	}

}
