<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

ini_set('memory_limit', '-1');

ob_start();

/**
 * start session
 */
session_start();

/**
 * start count cache
 */
$GLOBALS['count']['cache'] = 0;

/**
 * include PHP lib
 */
require __SYSTEMS_PATH . '/includes/PHP/lib.php';

/**
 * include version
 */
require __SYSTEMS_PATH . '/includes/config/ZenVERSION.php';

/**
 * include private file
 */
require __SYSTEMS_PATH . '/includes/config/ZenPRIVATE.php';

if (file_exists(__SYSTEMS_PATH . '/includes/config/ZenDB.php')) {
    /**
     * include database info
     */
    require __SYSTEMS_PATH . '/includes/config/ZenDB.php';
}

if (file_exists(__SYSTEMS_PATH . '/includes/config/ZenUSERCONFIG.php')) {
    /**
     * include main config
     */
    require __SYSTEMS_PATH . '/includes/config/ZenUSERCONFIG.php';
}

/**
 * include main config
 */
require __SYSTEMS_PATH . '/includes/config/ZenMAINCONFIG.php';

/**
 * include define
 */
require __SYSTEMS_PATH . '/includes/config/ZenDEFINE.php';

/**
 * include mime types
 */
require __SYSTEMS_PATH . '/includes/config/ZenMIMETYPES.php';


/**
 * set default timezone
 */
date_default_timezone_set(isset($zen['timezone_identifier']) ? $zen['timezone_identifier'] : 'Asia/Ho_Chi_Minh');