<?php
namespace FluidTYPO3\Vhs\Tests\Unit\ViewHelpers\Iterator;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\Tests\Unit\ViewHelpers\AbstractViewHelperTest;

/**
 * @protection on
 * @author Claus Due <claus@namelesscoder.net>
 * @package Vhs
 */
class ShiftViewHelperTest extends AbstractViewHelperTest {

	/**
	 * @test
	 * @dataProvider getRenderTestValues
	 * @param array $arguments
	 * @param mixed $expectedValue
	 */
	public function testRender(array $arguments, $expectedValue) {
		if (TRUE === isset($arguments['as'])) {
			$value = $this->executeViewHelperUsingTagContent('ObjectAccessor', 'variable', $arguments);
		} else {
			$value = $this->executeViewHelper($arguments);
			$value2 = $this->executeViewHelperUsingTagContent('ObjectAccessor', 'v', array(), array('v' => $arguments['subject']));
			$this->assertEquals($value, $value2);
		}
		$this->assertEquals($value, $expectedValue);
	}

	/**
	 * @return array
	 */
	public function getRenderTestValues() {
		return array(
			array(array('subject' => array()), NULL),
			array(array('subject' => array('foo', 'bar')), 'foo'),
			array(array('subject' => array('foo', 'bar'), 'as' => 'variable'), 'foo'),
			array(array('subject' => new \ArrayIterator(array('foo', 'bar'))), 'foo'),
			array(array('subject' => new \ArrayIterator(array('foo', 'bar')), 'as' => 'variable'), 'foo'),
		);
	}

	/**
	 * @test
	 * @dataProvider getErrorTestValues
	 * @param mixed $subject
	 */
	public function testThrowsErrorsOnInvalidSubjectType($subject) {
		$expected = 'Cannot get values of unsupported type: ' . gettype($subject);
		$result = $this->executeViewHelper(array('subject' => $subject));
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getErrorTestValues() {
		return array(
			array(0),
			array(NULL),
			array(new \DateTime()),
			array('invalid'),
			array(new \stdClass()),
		);
	}

}
