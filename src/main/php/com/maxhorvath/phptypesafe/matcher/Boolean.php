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
 * @package    com\maxhorvath\phptypesafe\matcher
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    SVN: $Id: Boolean.php 22 2008-08-28 15:32:25Z mhorvath $
 * @link       http://www.maxhorvath.com/
 * @since      File available since release 1.0.0
 */

/**
 * Define namespace
 */
namespace com\maxhorvath\phptypesafe\matcher;

require_once 'IMatcher.php';
require_once 'Bool.php';

/**
 * Class for values that are native bool.
 *
 * Alias for class Bool.
 *
 * @final
 * @category   PHP
 * @package    com\maxhorvath\phptypesafe\matcher
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
final class Boolean implements IMatcher
{
    // @codeCoverageIgnoreStart
    /**
     * Constructor.
     *
     * Method is private to disable a direct instanciation of this class.
     *
     * @return void
     *
     * @final
     * @access private
     * @since  Method available since release 1.0.0
     */
    final private function __construct()
    {
        // Empty by intention.
    }
    // @codeCoverageIgnoreEnd

    /**
     * Checks if the provided value passes the type check.
     *
     * @param  mixed $value The value to be checked.
     *
     * @return bool True if the provided value passes the type check.
     *
     * @final
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public static function isTypeSafe($value)
    {
        return Bool::isTypeSafe($value);
    }
}
