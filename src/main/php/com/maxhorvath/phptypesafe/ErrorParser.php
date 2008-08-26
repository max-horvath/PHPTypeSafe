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
 * Define namespace identifier
 */
use com::maxhorvath::phptypesafe::matcher as Matcher;

/**
 * Class handles parsing of PHP error messages.
 *
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
class ErrorParser
{
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
     * Method analyzes backtrace of an error message and returns the method
     * or function where the PH error happened.
     *
     * @param  string $methodName Method name to analyze.
     *
     * @return array Array containing class name and method name.
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function analyzeMethod($methodName)
    {
        Matcher::String::isTypeSafe($methodName);

        $_result = array('class' => null,
                         'function' => $methodName
                        );

        $_delimterPosition = strrpos($methodName, "::");

        if ($_delimterPosition !== false) {
            $_result['class'] = substr($methodName, 0, $_delimterPosition);
            $_result['function'] = substr($methodName, $_delimterPosition + 2);
        }

        return $_result;
    }

    /**
     * Method analyzes backtrace of an error message and returns the requested
     * type hint without namespace.
     *
     * @param  string $typeHint Type hint to analyze.
     *
     * @return string Class name of type hint without namespace.
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function analyzeTypeHint($typeHint)
    {
        Matcher::String::isTypeSafe($typeHint);

        $_result = $typeHint;

        $_delimterPosition = strrpos($typeHint, "::");

        if ($_delimterPosition !== false) {
            $_result = substr($typeHint, $_delimterPosition + 2);
        }

        return $_result;
    }

    /**
     * Method analyzes backtrace of an error message.
     *
     * @param  string $errorMessage Error message to analyze.
     *
     * @return array Array of details of error message.
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function analyzeErrorMessage($errorMessage)
    {
        Matcher::String::isTypeSafe($errorMessage);

        $_matcher = '/^Argument ([0-9]+) passed to ([a-zA-Z0-9_:]+)\(\) ' .
                    'must be an instance of ([a-zA-Z0-9_:]+), ' .
                    '([a-zA-Z0-9_:]+) given/';

        $_result = null;
        $_match = null;

        if (preg_match($_matcher, $errorMessage, $_match)) {
            $_methodMatcher = self::analyzeMethod($_match[2]);
            $_typeHintMatcher = self::analyzeTypeHint($_match[3]);

            $_result = array('argnum'   => $_match[1],
                             'class'    => $_methodMatcher['class'],
                             'function' => $_methodMatcher['function'],
                             'typehint' => $_typeHintMatcher,
                             'given'    => $_match[4],
                            );
        }

        return $_result;
    }

    /**
     * Parses an error message to find out if it is a type hint failure.
     *
     * @param  string $errorMessage Error message.
     *
     * @return array|null Details about error message.
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function parseErrorMessage($errorMessage)
    {
        Matcher::String::isTypeSafe($errorMessage);

        if (isset(self::$_parsedErrors[$errorMessage])) {
            return self::$_parsedErrors[$errorMessage];
        }

        self::$_parsedErrors[$errorMessage] = self::analyzeErrorMessage($errorMessage);

        return self::$_parsedErrors[$errorMessage];
    }
}
