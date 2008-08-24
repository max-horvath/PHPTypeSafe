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
 * Class that handles type hinting errors and checks type hint classes.
 *
 * Singleton.
 *
 * @final
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
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
     * Override clone method to throw exception.
     *
     * @return void
     *
     * @throws RuntimeException If singleton is tried to be cloned.
     *
     * @final
     * @access public
     * @since  Method available since release 1.0.0
     */
    final public function __clone()
    {
        throw new RuntimeException(
            'You cannot clone PHPTypeSafe (singleton object).'
        );
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
}
