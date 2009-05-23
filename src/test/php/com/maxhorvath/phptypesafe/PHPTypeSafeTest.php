<?php

/**
 * PHPTypeSafe
 *
 * Copyright (C) 2008  Max Horvath <info@maxhorvath.com>.
 *
 * This file is part of PHPTypeSafe.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   PHP
 * @package    com\maxhorvath\phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    SVN: $Id: PHPTypeSafeTest.php 22 2008-08-28 15:32:25Z mhorvath $
 * @link       http://www.maxhorvath.com/
 * @since      File available since release 1.0.0
 */

/**
 * Define namespace
 */
namespace com\maxhorvath\phptypesafe;

/**
 * Include required files.
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

/**
 * Test case for PHPTypeSafe.
 *
 * @category   PHP
 * @package    com\maxhorvath\phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
class PHPTypeSafeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests if PHPTypeSafe is a singleton.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testPHPTypeSafeIsSingleton()
    {
        $singleton = PHPTypeSafe::getInstance();
        $clone = PHPTypeSafe::getInstance();

        $this->assertSame($singleton, $clone);
    }

    /**
     * Tests if PHPTypeSafe stays a singleton when trying to clone PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testPHPTypeSafeStaysSingletonWhenCloning()
    {
        $this->setExpectedException('RuntimeException', 'You cannot clone PHPTypeSafe (singleton object).');

        $singleton = PHPTypeSafe::getInstance();
        $clone = clone $singleton;
    }

    /**
     * Tests if isTypeSafeClass detects type hints of PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testIsTypeSafeClassSuccess()
    {
        $_classes = array(__NAMESPACE__ . '\matcher\Bool',
                          __NAMESPACE__ . '\matcher\Boolean',
                          __NAMESPACE__ . '\matcher\Double',
                          __NAMESPACE__ . '\matcher\Float',
                          __NAMESPACE__ . '\matcher\Int',
                          __NAMESPACE__ . '\matcher\Integer',
                          __NAMESPACE__ . '\matcher\Long',
                          __NAMESPACE__ . '\matcher\Real',
                          __NAMESPACE__ . '\matcher\Resource',
                          __NAMESPACE__ . '\matcher\String',
                         );

        foreach ($_classes as $_element) {
            $this->assertTrue(PHPTypeSafe::getInstance()->isTypeSafeClass($_element), "Failure with $_element");
        }
    }

    /**
     * Tests if isTypeSafeClass declines non-existing type hints of PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testIsTypeSafeClassDeclineNonExistingClass()
    {
        $this->assertFalse(PHPTypeSafe::getInstance()->isTypeSafeClass(__NAMESPACE__ . '\matcher::TestClass'));
    }

    /**
     * Tests if isTypeSafeClass declines existing type hints not part of
     * PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testIsTypeSafeClassDeclineNonTypeSafeClass()
    {
        $this->assertFalse(PHPTypeSafe::getInstance()->isTypeSafeClass(__NAMESPACE__ . '\matcher::IMatcher'));
    }

    /**
     * Tests if solveTypeHintFailure solves type hints of PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testSolveTypeHintFailureSuccess()
    {
        $_classes = array(array('typehint' => 'bool',
                                'value' => true),
                          array('typehint' => 'boolean',
                                'value' => false),
                          array('typehint' => 'double',
                                'value' => 12.34),
                          array('typehint' => 'float',
                                'value' => 12.34),
                          array('typehint' => 'int',
                                'value' => 123),
                          array('typehint' => 'integer',
                                'value' => 123),
                          array('typehint' => 'long',
                                'value' => 123),
                          array('typehint' => 'real',
                                'value' => 12.34),
                          array('typehint' => 'resource',
                                'value' => fopen('ErrorHandlerTest.php', 'r')),
                          array('typehint' => 'string',
                                'value' => 'Test'),
                         );

        foreach ($_classes as $_element) {
            $this->assertTrue(PHPTypeSafe::getInstance()
                                         ->solveTypeHintFailure($_element['typehint'], $_element['value']));
        }
    }

    /**
     * Tests if solveTypeHintFailure solves failing type hints of PHPTypeSafe.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testSolveTypeHintFailureFails()
    {
        $_classes = array(array('typehint' => 'bool',
                                'value' => 123),
                          array('typehint' => 'boolean',
                                'value' => 123),
                          array('typehint' => 'double',
                                'value' => 'Test'),
                          array('typehint' => 'float',
                                'value' => 'Test'),
                          array('typehint' => 'int',
                                'value' => 'Test'),
                          array('typehint' => 'integer',
                                'value' => 'Test'),
                          array('typehint' => 'long',
                                'value' => 'Test'),
                          array('typehint' => 'real',
                                'value' => 'Test'),
                          array('typehint' => 'resource',
                                'value' => 123),
                          array('typehint' => 'string',
                                'value' => 123),
                         );

        foreach ($_classes as $_element) {
            $this->assertFalse(PHPTypeSafe::getInstance()
                                          ->solveTypeHintFailure($_element['typehint'], $_element['value']));
        }
    }

    /**
     * Tests if solveTypeHintFailure declines illegal call.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testSolveTypeHintFailureFailsOnIllegalTypeHint()
    {
        $this->assertFalse(PHPTypeSafe::getInstance()->solveTypeHintFailure('Test', 'Test'));
    }
}
