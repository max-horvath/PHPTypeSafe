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
 * @version    SVN: $Id: assembly.php 26 2008-09-01 10:51:41Z mhorvath $
 * @link       http://www.maxhorvath.com/
 * @since      File available since release 1.0.0
 */

if (count($_SERVER['argv']) != 2) {
    throw new RuntimeException(
        'Please provide the desired filename for the .phar archive.
    ');
}

$ini = ini_get_all('phar');

if ($ini['phar.readonly']['local_value'] != 0) {
    throw new RuntimeException(
        'The php.ini setting phar.readonly needs to be set to 0 in ' .
        'order to create Phar archives.'
    );
}

$file = $_SERVER['argv'][1];
$filename = substr($file, strlen('../../bin/'));

$phar = new Phar($file, 0, $filename);
$phar->buildFromDirectory('../main/php', '/\.php$/');

$stub = '<?php ' .
        "Phar::mapPhar('$filename'); " .
        'require_once ' .
        "'phar://$filename/com/maxhorvath/phptypesafe/PHPTypeSafe.php'; " .
        'com\maxhorvath\phptypesafe\PHPTypeSafe::setUp(); ' .
        '__HALT_COMPILER();';

$phar->setStub($stub);
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();
