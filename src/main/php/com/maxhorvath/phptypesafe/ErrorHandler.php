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
 * Define includes
 */
require_once 'ErrorParser.php';

/**
 * Define namespace identifier
 */
use com::maxhorvath::phptypesafe::matcher as Matcher;

/**
 * Class registers itself as a PHP Error Handler and proceeds to check all
 * catchable native PHP errors for type hinting failures.
 *
 * @abstract
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
abstract class ErrorHandler
{
    /**
     * Error handler that was replaced by the one from this object
     *
     * @static
     * @var    function|method|null
     * @access public
     * @since  Property available since release 1.0.0
     */
    public static $oldErrorHandler;

    /**
     * Array of parsed error messages.
     *
     * @static
     * @var    array
     * @access private
     * @since  Property available since release 1.0.0
     */
    private static $_parsedErrors = array();

    /**
     * Method sets up the error handler.
     *
     * @return function|method|null Old error handler
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function setUp()
    {
        self::$oldErrorHandler = set_error_handler(__NAMESPACE__ . '::ErrorHandler::analyzeError');

        return self::$oldErrorHandler;
    }

    /**
     * Method tears down the error handler and restores the previous error
     * handler function.
     *
     * @return bool This function always returns True.
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function tearDown()
    {
        return restore_error_handler();
    }

    /**
     * Method analyzes and handles errors.
     *
     * @param  int    $code    Contains the level of the error raised.
     * @param  string $message Contains the error message.
     * @param  string $file    Contains the filename that the error was raised in.
     * @param  int    $line    Contains the line number the error was raised at.
     * @param  array  $context Array that points to the active symbol table at
     *                         the point the error occurred.
     *
     * @return void
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function analyzeError($code, $message, $file = '', $line = 0, array $context = array())
    {
        Matcher::Int::isTypeSafe($code);
        Matcher::String::isTypeSafe($message);
        Matcher::String::isTypeSafe($file);
        Matcher::Int::isTypeSafe($line);

        if ($code == E_RECOVERABLE_ERROR) {
            $_parsedError = ErrorParser::parseErrorMessage($message);

            if (is_array($_parsedError)) {
                $_backtrace = debug_backtrace();
                $_value = @$_backtrace[1]['args'][$_parsedError['argnum'] - 1];

                if (PHPTypeSafe::getInstance()->solveTypeHintFailure($_parsedError['typehint'], $_value)) {
                    return;
                } else {
                    $_typeHint = 'com::maxhorvath::phptypesafe::matcher::' .
                                 strtolower($_parsedError['typehint']);

                    $_details = ErrorParser::analyzeErrorMessage($message);

                    if (PHPTypeSafe::getInstance()->isTypeSafeClass($_typeHint)) {
                        throw new ErrorException('Argument ' . $_details['argnum'] . ' ' .
                                                 'passed to ' .
                                                 $_details['class'] . '::' . $_details['function'] . ' ' .
                                                 'must be of type ' . $_details['typehint'] . ', ' .
                                                 $_details['given'] . ' given, ' .
                                                 'called in ' . $file . ' ' .
                                                 'on line ' . $line,
                                                 $code, E_RECOVERABLE_ERROR, $file, $line);
                    }
                }
            // @codeCoverageIgnoreStart
            }
            // @codeCoverageIgnoreEnd
        }

        if (self::$oldErrorHandler) {
            return call_user_func(self::$oldErrorHandler, $code, $message, $file, $line, $context);
        } else {
            return false;
        }
    }
}
