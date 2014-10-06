<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Oct 30, 2013
 * Time: 5:30:59 PM
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */

/**
 * Load utility
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
	
	/**
	 * Test inheritance
	 */
	public function testInheritance() {
		$className = '_ArrayObjectA';
		if (!class_exists($className)) {
			//@codingStandardsIgnoreStart
			eval("class $className extends ArrayObjectA {}");
			//@codingStandardsIgnoreEnd
		}
		$Array = new $className(array('one', 'two', 'three'));
		$this->assertInstanceOf($className, $Array);
		$this->assertInstanceOf($className, $Array->merge(new ArrayObjectA(array('four'))), 'Merge with ArrayObjectA');
		$this->assertInstanceOf($className, $Array->merge(new $className(array('four'))), 'Merge with self class');
		$this->assertInstanceOf($className, $Array->filter(function() { 
			return true; 
		}), 'Filter');
		$this->assertInstanceOf($className, $Array->map(function($Item) { 
			return $Item; 
		}), 'Map');
		$this->assertInstanceOf($className, $Array->group(function() { 
			return 1; 
		}), 'Group');
		$this->assertInstanceOf($className, $Array->unique(function() { 
			return 1;
		}), 'Unique');
		$this->assertInstanceOf($className, $Array->resetKeys(), 'Reset keys');
	}
	
	/**
	 * Test slice
	 * 
	 * @param array $array
	 * @param int $offset
	 * @param int $length
	 * @param bool $preserveKeys
	 * @param array $result
	 * 
	 * @dataProvider sliceProvider
	 */
	public function testSlice(array $array, $offset, $length, $preserveKeys, array $result) {
		$this->assertSame((new ArrayObjectA($array))->slice($offset, $length, $preserveKeys)->getArrayCopy(), $result);
	}
	
	/**
	 * Test arraySort integrity
	 */
	public function testMultisort() {
		$expected = array(
			'item1' => array(
				'weight' => 4,
				'diff' => array(
					1 => 10,
					7 => -5,
					30 => 0
				)
			),
			'item2' => array(
				'weight' => 3,
				'diff' => array(
					1 => 10,
					7 => -5,
					30 => 0
				)
			),
			'item3' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -5,
					30 => 0
				)
			),
			'item4' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -10,
					30 => 0
				)
			),
			'item5' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -10,
					30 => -1
				)
			),
			'item6' => array(
				'weight' => 2,
				'diff' => array(
					1 => 3,
					7 => 4,
					30 => 5
				)
			),
			'item7' => array(
				'weight' => 1,
				'diff' => array(
					1 => 30,
					7 => 40,
					30 => 50
				)
			),
			'item8' => array(
				'weight' => 1,
				'diff' => array(
					1 => false,
					7 => false,
					30 => false
				)
			)
		);

		$test = $expected;

		$ashuffle = function (&$array) {
			$keys = array_keys($array);
			shuffle($keys);
			$array = array_merge(array_flip($keys), $array);
			return true;
		};

		$ashuffle($test);
		$Test = new ArrayObjectA($test);
		
		$params = array(
			'weight' => 'desc',
			'diff.1' => 'desc',
			'diff.7' => 'desc',
			'diff.30' => 'desc'
		);
		$this->assertNotSame($expected, $Test->getArrayCopy());
		$this->assertSame($expected, $Test->multisort($params)->getArrayCopy());

		$expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
		$test = $expected;
		shuffle($test);
		$Test = new ArrayObjectA($test);
		$params = 'asc';
		$this->assertNotSame($expected, $Test->getArrayCopy());
		$this->assertSame($expected, $Test->multisort($params)->getArrayCopy());

		$expected = array(
			'item1' => 1,
			'item2' => 2,
			'item3' => 3,
			'item4' => 4,
			'item5' => 5
		);
		$test = $expected;
		$ashuffle($test);
		if ($test === $expected) {
			$ashuffle($test);
		}
		$Test = new ArrayObjectA($test);
		$params = 'asc';
		$this->assertNotSame($expected, $Test->getArrayCopy());
		$this->assertSame($expected, $Test->multisort($params)->getArrayCopy());
	}

	/**
	 * Data provider for testMerge
	 * 
	 * @return array
	 */
	public function mergeProvider() {
		return array(
			array(new ArrayObjectA(array('a', 'c', 'd')), new ArrayObjectA(array('b', 'c', 'e')), new ArrayObjectA(array('a', 'c', 'd', 'b', 'c', 'e')), true),
			array(new ArrayObjectA(array('a', 'c', 'd')), new ArrayObjectA(array('b', 'c', 'e')), new ArrayObjectA(array('a', 'c', 'd', 'b')), false)
		);
	}

	/**
	 * Data provider for testMap
	 * 
	 * @return array
	 */
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

	/**
	 * Data provider for testFilter
	 * 
	 * @return array
	 */
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

	/**
	 * Data provider for testReduce
	 * 
	 * @return array
	 */
	public function reduceProvider() {
		return array(
			array(new ArrayObjectA(array(1, 2, 3)), function($e, $acc) {
				return (int)$acc + $e;
			}, 1 + 2 + 3, true),
			array(new ArrayObjectA(array('a', 'c', 'd')), function($e, $acc) {
				return (int)$acc + 1;
			}, 0, false)
		);
	}

	/**
	 * Data provider for testGroup
	 * 
	 * @return array
	 */
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

	/**
	 * Data provider for testSerialize
	 * 
	 * @return array
	 */
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

	/**
	 * Data provider for testUnique
	 * 
	 * @return array
	 */
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
	
	/**
	 * Data provider for testSlice
	 * 
	 * @return array
	 */
	public function sliceProvider() {
		return array(
			//set #0
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				1,
				//length
				null,
				//preserve_key
				true,
				//result
				array(
					1 => 'two', 
					2 => 'three'
				)
			),
			//set #1
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				1,
				//length
				null,
				//preserve_key
				false,
				//result
				array(
					0 => 'two', 
					1 => 'three'
				)
			),
			//set #1
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				1,
				//length
				1,
				//preserve_key
				true,
				//result
				array(
					1 => 'two',
				)
			),
			//set #2
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				1,
				//length
				1,
				//preserve_key
				false,
				//result
				array(
					0 => 'two',
				)
			),
			//set #3
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				3,
				//length
				10,
				//preserve_key
				false,
				//result
				array(
				)
			),
			//set #4
			array(
				//array
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
					),
				//offset
				0,
				//length
				10,
				//preserve_key
				true,
				//result
				array(
					0 => 'one', 
					1 => 'two', 
					2 => 'three'
				)
			),
		);
	}

}
