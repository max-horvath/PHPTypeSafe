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
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    SVN: $Id: PHPTypeSafe.php 22 2008-08-28 15:32:25Z mhorvath $
 * @link       http://www.maxhorvath.com/
 * @since      File available since release 1.0.0
 */

/**
 * Define namespace
 */
namespace com\maxhorvath\phptypesafe;

/**
 * Define includes
 */
require_once 'ErrorHandler.php';
require_once 'matcher/Bool.php';
require_once 'matcher/Boolean.php';
require_once 'matcher/Double.php';
require_once 'matcher/Float.php';
require_once 'matcher/Int.php';
require_once 'matcher/Integer.php';
require_once 'matcher/Long.php';
require_once 'matcher/Real.php';
require_once 'matcher/Resource.php';
require_once 'matcher/String.php';

/**
 * Define namespace identifier
 */
use com\maxhorvath\phptypesafe\matcher as Matcher;

/**
 * Class that handles type hinting errors and checks type hint classes.
 *
 * Singleton.
 *
 * @final
 * @category   PHP
 * @package    com\maxhorvath\phptypesafe
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
final class PHPTypeSafe
{
    /**
     * Single instance of PHPTypeSafe.
     *
     * @static
     * @var    PHPTypeSafe
     * @access private
     * @since  Property available since release 1.0.0
     */
    private static $_instance;

    /**
     * Array of available type safety classes.
     *
     * @var    array
     * @access private
     * @since  Property available since release 1.0.0
     */
    private $_typeSafetyClasses = array();

    /**
     * Constructor.
     *
     * Activates the error handling. Method is private to disable a direct
     * instanciation of this class.
     *
     * @return void
     *
     * @final
     * @access private
     * @since  Method available since release 1.0.0
     */
    final private function __construct()
    {
        ErrorHandler::setUp();
    }

    /**
     * Override clone method to throw exception.
     *
     * @return void
     *
     * @throws RuntimeException if singleton is tried to be cloned.
     *
     * @final
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public function __clone()
    {
        throw new \RuntimeException('You cannot clone PHPTypeSafe (singleton object).');
    }

    /**
     * Sets up the single instance of this handler.
     *
     * @return void
     *
     * @final
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public static function setUp()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
    }

    /**
     * Returns the single instance of this handler.
     *
     * @return PHPTypeSafe The singleton instance.
     *
     * @final
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public static function getInstance()
    {
        self::setUp();

        return self::$_instance;
    }

    /**
     * Indicates if a class is a type hint class of PHPTypeSafe.
     *
     * @param  string $className The name of the class.
     *
     * @return bool True if the class is a type hint class.
     *
     * @final
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public function isTypeSafeClass($className)
    {
        Matcher\String::isTypeSafe($className);

        $className = strtolower($className);

        if (isset($this->_typeSafetyClasses[$className]) && is_bool($this->_typeSafetyClasses[$className])) {
            return $this->_typeSafetyClasses[$className];
        }

        $this->_typeSafetyClasses[$className] = false;

        if (class_exists($className)) {
            if (in_array('com\maxhorvath\phptypesafe\matcher\IMatcher', class_implements($className))) {
                $this->_typeSafetyClasses[$className] = true;
            }
        }

        return $this->_typeSafetyClasses[$className];
    }

    /**
     * Solves a type hint failure, deciding to continue or not.
     *
     * @param string $typeHint The type hint used.
     * @param mixed  $value The value that failed the type hint.
     *
     * @return bool Whether to continue or not.
     */
    final public function solveTypeHintFailure($typeHint, $value)
    {
        Matcher\String::isTypeSafe($typeHint);

        $typeHint = 'com\maxhorvath\phptypesafe\matcher\\' .
                    strtolower($typeHint);

        if ($this->isTypeSafeClass($typeHint)) {
            return call_user_func($typeHint . '::isTypeSafe', $value);
        }

        return false;
    }
}
