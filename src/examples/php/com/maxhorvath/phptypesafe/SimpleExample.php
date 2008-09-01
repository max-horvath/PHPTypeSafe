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
 * @subpackage examples
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
require_once 'com.maxhorvath.phptypesafe.phar';

/**
 * Test class.
 *
 * @category   PHP
 * @package    com::maxhorvath::phptypesafe
 * @subpackage examples
 * @author     Max Horvath <info@maxhorvath.com>
 * @copyright  2008 Max Horvath <info@maxhorvath.com>
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public Licence Version 3
 * @version    Release: 1.0.0
 * @link       http://www.maxhorvath.com/
 * @since      Class available since release 1.0.0
 */
class Foo
{
    /**
     * Test method with two scalar type hint types.
     *
     * @param  string $msg     Message to return.
     * @param  int    $counter Counter to reflect run of method.
     *
     * @return string
     *
     * @static
     * @access public
     * @since  Method available since release 1.0.0
     */
    public static function bar(string $msg, int $counter)
    {
        echo $counter . '. run: ' . $msg . "\n\n";
    }
}

$postCount = 0;

Foo::bar('test', ++$postCount);

echo "The next run will result in an an ErrorException: \n\n";

Foo::bar(false, ++$postCount);

?>