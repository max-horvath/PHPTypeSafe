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
require_once '../../../../../main/php/com/maxhorvath/phptypesafe/ErrorParser.php';

/**
 * Test case for ErrorParser.
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
class ErrorParserTest extends ::PHPUnit_Framework_TestCase
{
    /**
     * Tests if analyzeMethod doesn't touch function names.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeMethodDoesNotTouchFunctionNames()
    {
        $this->assertEquals(array('class' => null, 'function' => 'testFunction'),
                            ErrorParser::analyzeMethod('testFunction'));
    }

    /**
     * Tests if analyzeMethod correctly seperates method names from class names.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeMethodSeperatesFromClassNames()
    {
        $this->assertEquals(array('class' => 'TestClass', 'function' => 'testMethod'),
                            ErrorParser::analyzeMethod('TestClass::testMethod'));
    }

    /**
     * Tests if analyzeMethod correctly seperates method names from namespaces.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeMethodSeperatesFromNameSpaces()
    {
        $this->assertEquals(array('class' => 'TestNamespace::TestClass', 'function' => 'testMethod'),
                            ErrorParser::analyzeMethod('TestNamespace::TestClass::testMethod'));
    }

    /**
     * Tests if analyzeTypeHint doesn't touch class names.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeTypeHintDoesNotTouchClassNames()
    {
        $this->assertEquals('TestClass', ErrorParser::analyzeTypeHint('TestClass'));
    }

    /**
     * Tests if analyzeTypeHint correctly seperates class names from namespaces.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeTypeHintSeperatesFromNamespaces()
    {
        $this->assertEquals('TestClass', ErrorParser::analyzeTypeHint('TestNamespace::TestClass'));
    }

    /**
     * Tests if analyzeErrorMessage correctly extracts details from type
     * hinting releated PHP error messages in the global namespace.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeErrorMessageExtractsFromGlobalNamespace()
    {
        $_message = 'Argument 1 passed to TestClass::testMethod() must be an ' .
                    'instance of bool, ' .
                    'string given';

        $_expected = array('argnum'   => 1,
                           'class'    => 'TestClass',
                           'function' => 'testMethod',
                           'typehint' => 'bool',
                           'given'    => 'string',
                          );

        $this->assertEquals($_expected, ErrorParser::analyzeErrorMessage($_message));
    }

    /**
     * Tests if analyzeErrorMessage correctly extracts details from type
     * hinting releated PHP error messages in private namespaces.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeErrorMessageExtractsFromPrivateNamespace()
    {
        $_message = 'Argument 1 passed to TestNamespace::TestClass::testMethod() must be an ' .
                    'instance of TestNamespace::bool, ' .
                    'string given';

        $_expected = array('argnum'   => 1,
                           'class'    => 'TestNamespace::TestClass',
                           'function' => 'testMethod',
                           'typehint' => 'bool',
                           'given'    => 'string',
                          );

        $this->assertEquals($_expected, ErrorParser::analyzeErrorMessage($_message));
    }

    /**
     * Tests if analyzeErrorMessage correctly discards details from type
     * hinting releated PHP error messages in the global namespace when
     * finding non-scalar values.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeErrorMessageExtractsClassFromGlobalNamespace()
    {
        $_message = 'Argument 1 passed to TestClass::testMethod() must be an ' .
                    'instance of bool, ' .
                    'instance of ErrorTestClass given';

        $_expected = null;

        $this->assertEquals($_expected, ErrorParser::analyzeErrorMessage($_message));
    }

    /**
     * Tests if analyzeErrorMessage correctly discards details from type
     * hinting releated PHP error messages in private namespaces when
     * finding non-scalar values.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testAnalyzeErrorMessageExtractsClassFromPrivateNamespace()
    {
        $_message = 'Argument 1 passed to TestNamespace::TestClass::testMethod() must be an ' .
                    'instance of TestNamespace::bool, ' .
                    'instance of TestNamespace::ErrorTestClass given';

        $_expected = null;

        $this->assertEquals($_expected, ErrorParser::analyzeErrorMessage($_message));
    }

    /**
     * Tests if parseErrorMessage safes parsed error message in array.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testParseErrorMessageSafesMessageInArray()
    {
        $_message = 'Argument 1 passed to Cache::TestNamespace::TestClass::testMethod() must be an ' .
                    'instance of TestNamespace::bool, ' .
                    'string given';

        $_expected = array('argnum'   => 1,
                           'class'    => 'Cache::TestNamespace::TestClass',
                           'function' => 'testMethod',
                           'typehint' => 'bool',
                           'given'    => 'string',
                          );

        ErrorParser::parseErrorMessage($_message);

        $_actual = $this->readAttribute('com::maxhorvath::phptypesafe::ErrorParser', '_parsedErrors');

        $this->assertEquals($_expected, $_actual[$_message]);
    }

    /**
     * Tests if parseErrorMessage caches parsed error messages.
     *
     * @return void
     *
     * @access public
     * @since  Method available since release 1.0.0
     */
    public function testParseErrorMessageCachesMessageInArray()
    {
        $_message = 'Argument 1 passed to Cache::TestNamespace::TestClass::testMethod() must be an ' .
                    'instance of TestNamespace::bool, ' .
                    'string given';

        $_expected = array('argnum'   => 1,
                           'class'    => 'Cache::TestNamespace::TestClass',
                           'function' => 'testMethod',
                           'typehint' => 'bool',
                           'given'    => 'string',
                          );

        $_errorParser = $this->getMock('com::maxhorvath::phptypesafe::ErrorParser', array('analyzeErrorMessage'));
        $_errorParser->expects($this->never())->method('analyzeErrorMessage');

        $_errorParser->parseErrorMessage($_message);

        $_actual = $this->readAttribute('com::maxhorvath::phptypesafe::ErrorParser', '_parsedErrors');

        $this->assertEquals($_expected, $_actual[$_message]);
    }
}
