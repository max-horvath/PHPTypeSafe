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
require_once '../../../../../main/php/com/maxhorvath/phptypesafe/PHPTypeSafe.php';

/**
 * Test case for PHPTypeSafe.
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
class PHPTypeSafeTest extends ::PHPUnit_Framework_TestCase
{
    /**
     * Tests if PHPTypeSafe is a singleton.
     *
     * @return void
     *
     * @covers PHPTypeSafe::setup()
     * @covers PHPTypeSafe::getInstance()
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
     * @covers PHPTypeSafe::getInstance()
     * @covers PHPTypeSafe::__clone()
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
}
