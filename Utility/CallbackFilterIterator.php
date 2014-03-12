<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Mar 12, 2014
 * Time: 6:32:29 PM
 * 
 */
if (!class_exists('CallbackFilterIterator')) {

	class CallbackFilterIterator extends FilterIterator implements Iterator, Traversable, OuterIterator {

		/**
		 * (PHP 5 &gt;= 5.4.0)<br/>
		 * Create a filtered iterator from another iterator
		 * @link http://php.net/manual/en/callbackfilteriterator.construct.php
		 * @param Iterator $iterator
		 * @param $callback
		 */
		public function __construct(Iterator $iterator, $callback) {
			parent::__construct($iterator);
			$this->callback = $callback;
		}

		/**
		 * (PHP 5 &gt;= 5.4.0)<br/>
		 * Calls the callback with the current value, the current key and the inner iterator as arguments
		 * @link http://php.net/manual/en/callbackfilteriterator.accept.php
		 * @return string <b>TRUE</b> to accept the current item, or <b>FALSE</b> otherwise.
		 */
		public function accept() {
			$callback = $this->callback;
			return $callback($this->current());
		}

	}

}