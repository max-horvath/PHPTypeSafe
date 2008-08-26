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
 * @package    com::maxhorvath::phptypesafe
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    SVN: $Id$
 * @link       http://www.maxhorvath.com/
 * @since      File available since release 1.0.0
 */

/**
 * Define namespace
 */
namespace com::maxhorvath::phptypesafe;

/**
 * Include required files.
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once '../../../../../main/php/com/maxhorvath/phptypesafe/ErrorHandler.php';

/**
 * Test class for ErrorHandlerTest to test type hint failures.
 *
 * @final
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
final class TypeHintTestClass
{
    /**
     * Testmethod to initiate a type hint error.
     *
     * @param  Test Dummy type hint.
     *
     * @return bool Always returns true.
     *
     * @final
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public static function initiateTypeHintError(bool $triggerError)
    {
        return true;
    }
}

/**
 * Test class for ErrorHandlerTest to initaite type hint failure.
 *
 * Empty by intention.
 *
 * @final
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
final class TypeHintError
{
    // Empty by intention.
}

/**
 * Dummy error handler to test the fallback to an existing error handler.
 *
 * @final
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
final class TestErrorhandler
{

    /**
     * Property holding whether TestErrorHandler has been called.
     *
     * @static
     * @var    bool
     * @access public
     * @since  Property available since release 1.0.0
     */
    public static $handlerCalled = false;

    final public static function handleError($code, $message, $file, $line, $context)
    {
        self::$handlerCalled = true;
    }
}

/**
 * Test case for ErrorHandler.
 *
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @subpackage test
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
class ErrorHandlerTest extends ::PHPUnit_Framework_TestCase
{

    /**
     * Sets up test case
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function setUp()
    {
        ErrorHandler::setUp();
    }

    /**
     * Tear down test case
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function tearDown()
    {
        ErrorHandler::tearDown();
    }

    /**
     * Tests if error handler passes on correct type hint.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testErrorHandlerPasses()
    {
        $_testPassed = true;

        try {
            TypeHintTestClass::initiateTypeHintError(true);
        } catch (ErrorException $e) {
            $_testPassed = false;
        }

        $this->assertTrue($_testPassed);
    }

    /**
     * Tests if error handler triggers error exception on wrong type hint.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testErrorHandlerTriggersExceptions()
    {
        $this->setExpectedException('ErrorException');

        TypeHintTestClass::initiateTypeHintError('test');
    }

    /**
     * Tests if error handler triggers old error handler when not handling
     * type hint errors that do not require scalar classes.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testErrorHandlerTriggersOldErrorHandler()
    {
        ErrorHandler::tearDown();
        set_error_handler(__NAMESPACE__ . '::TestErrorhandler::handleError');
        ErrorHandler::setUp();

        if (TestErrorhandler::$handlerCalled) {
            $this->fail('Old error handler already called!');
        }

        $_errorReporting = error_reporting(0);

        TypeHintTestClass::initiateTypeHintError(new TypeHintError());

        error_reporting($_errorReporting);

        $this->assertTrue(TestErrorhandler::$handlerCalled, 'Old error handler has not been activated!');
    }

    /**
     * Tests if error handler triggers PHP error when not handling
     * type hint errors that do not require scalar classes and no exception
     * handler has been set.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testErrorHandlerTriggersPHPError()
    {
        ErrorHandler::$oldErrorHandler = null;

        $_errorReporting = error_reporting(0);

        TypeHintTestClass::initiateTypeHintError(new TypeHintError());

        $_backtrace = debug_backtrace();
        $_message = 'PHP Catchable fatal error:  Argument 1 passed to com::' .
                    'maxhorvath::phptypesafe::TypeHintTestClass::' .
                    'initiateTypeHintError() must be an instance of com::' .
                    'maxhorvath::phptypesafe::bool, instance of com::' .
                    'maxhorvath::phptypesafe::TypeHintError given';

        $_iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($_backtrace));
        $_valueFound = false;

        foreach ($_iterator as $_element) {
            if ($_element == $_message) {
                $_valueFound = true;
            }
        }

        $this->assertTrue($_valueFound, 'PHP error not thrown!');

        error_reporting($_errorReporting);
    }
}
