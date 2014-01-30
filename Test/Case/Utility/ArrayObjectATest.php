<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Oct 30, 2013
 * Time: 5:30:59 PM
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'Utility' . DIRECTORY_SEPARATOR . 'ArrayObjectA.php';

/**
 * Advanced Array Object Test
 * 
 * @package ArrayObjectATest
 * @subpackage Utility
 */
class ArrayObjectATest extends PHPUnit_Framework_TestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * Test merge
	 * 
	 * @param ArrayObjectA $Array1
	 * @param ArrayObjectA $Array2
	 * @param ArrayObjectA $Result
	 * @param bool $same
	 * 
	 * @dataProvider mergeProvider
	 */
	public function testMerge($Array1, $Array2, $Result, $same) {
		if ($same) {
			$this->assertEquals($Array1->merge($Array2), $Result);
		} else {
			$this->assertNotEquals($Array1->merge($Array2), $Result);
		}
	}

	/**
	 * Test map
	 * 
	 * @param ArrayObjectA $Array1
	 * @param callable $callback
	 * @param ArrayObjectA $Result
	 * @param bool $same
	 * 
	 * @dataProvider mapProvider
	 */
	public function testMap($Array1, $callback, $Result, $same) {
		if ($same) {
			$this->assertEquals($Array1->map($callback), $Result);
		} else {
			$this->assertNotEquals($Array1->map($callback), $Result);
		}
	}

	/**
	 * Test filter
	 * 
	 * @param ArrayObjectA $Array1
	 * @param callable $callback
	 * @param ArrayObjectA $Result
	 * @param bool $same
	 * 
	 * @dataProvider filterProvider
	 */
	public function testFilter($Array1, $callback, $Result, $same) {
		if ($same) {
			$this->assertEquals($Array1->filter($callback), $Result);
		} else {
			$this->assertNotEquals($Array1->filter($callback), $Result);
		}
	}

	/**
	 * Test reduce
	 * 
	 * @param ArrayObjectA $Array1
	 * @param callable $callback
	 * @param mixed $result
	 * @param bool $same
	 * 
	 * @dataProvider reduceProvider
	 */
	public function testReduce($Array1, $callback, $result, $same) {
		if ($same) {
			$this->assertEquals($Array1->reduce($callback), $result);
		} else {
			$this->assertNotEquals($Array1->reduce($callback), $result);
		}
	}

	/**
	 * Test group
	 * 
	 * @param ArrayObjectA $Array1
	 * @param callable $callback
	 * @param ArrayObjectA $Result
	 * 
	 * @dataProvider groupProvider
	 */
	public function testGroup($Array1, $callback, $Result) {
		$this->assertEquals($Array1->group($callback), $Result);
	}

	/**
	 * Test json serialization
	 * 
	 * @param ArrayObjectA $Array
	 * @param string $result
	 * 
	 * @dataProvider jsonProvider
	 */
	public function testSerialize($Array, $result) {
		$this->assertSame(json_encode($Array), $result);
	}
	
	/**
	 * Test unique
	 * 
	 * @param ArrayObjectA $Array1
	 * @param callable $callback
	 * @param ArrayObjectA $Result
	 * 
	 * @dataProvider uniqueProvider
	 */
	public function testUnique($Array1, $callback, $Result) {
		$this->assertEquals($Array1->unique($callback), $Result);
	}

	public function mergeProvider() {
		return array(
			array(new ArrayObjectA(array('a', 'c', 'd')), new ArrayObjectA(array('b', 'c', 'e')), new ArrayObjectA(array('a', 'c', 'd', 'b', 'c', 'e')), true),
			array(new ArrayObjectA(array('a', 'c', 'd')), new ArrayObjectA(array('b', 'c', 'e')), new ArrayObjectA(array('a', 'c', 'd', 'b')), false)
		);
	}

	public function mapProvider() {
		return array(
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e) {
			return "$e 1";
		}, new ArrayObjectA(array('a 1', 'c 1', 'd 1')), true),
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e) {
			return "$e 1";
		}, new ArrayObjectA(array('a 2', 'c 2', 'd 2')), false)
		);
	}

	public function filterProvider() {
		return array(
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e) {
			return $e == 'c';
		}, new ArrayObjectA(array('c')), true),
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e) {
			return $e == 'd';
		}, new ArrayObjectA(array('a', 'c', 'd')), false)
		);
	}

	public function reduceProvider() {
		return array(
			array(new ArrayObjectA(array(1, 2, 3)), function($e, $acc) {
			return (int) $acc + $e;
		}, 1 + 2 + 3, true),
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e, $acc) {
			return (int) $acc + 1;
		}, 0, false)
		);
	}

	public function groupProvider() {
		return array(
			array(
				new ArrayObjectA(array(
					array('g' => 1, 'n' => 1),
					array('g' => 2, 'n' => 2),
					array('g' => 1, 'n' => 3)
						)
				), function($e) {
			return $e['g'];
		}, new ArrayObjectA(array(
					1 => array(
						array('g' => 1, 'n' => 1),
						array('g' => 1, 'n' => 3)
					),
					2 => array(
						array('g' => 2, 'n' => 2)
					)
						)
				))
		);
	}

	public function jsonProvider() {
		$arrays = array(
			array(
				array('g' => 1, 'n' => 1),
				array('g' => 2, 'n' => 2),
				array('g' => 1, 'n' => 3)
			),
			array(234),
			array(
				1 => array(
					array('g' => 1, 'n' => 1),
					array('g' => 1, 'n' => 3)
				),
				2 => array(
					array('g' => 2, 'n' => 2)
				)
			)
		);

		$data = array();
		foreach ($arrays as $array) {
			$data[] = array(new ArrayObjectA($array), json_encode($array));
		}

		return $data;
	}
	
	public function uniqueProvider() {
		return array(
			array(
				new ArrayObjectA(array(
					array('g' => 1, 'n' => 1),
					array('g' => 2, 'n' => 2),
					array('g' => 1, 'n' => 3)
						)
				), function($e) {
					return $e['g'];
				},
				new ArrayObjectA(array(
					array('g' => 1, 'n' => 1),
					array('g' => 2, 'n' => 2)
						)
				)
			)
		);
	}

}
