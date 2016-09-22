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

/**
 * Prefix database
 */
$zen['config']['table_prefix'] = 'zen_cms_';

/**
 * Router default when router is empty
 */
$zen['config']['default_router'] = 'blog';
$zen['config']['default_router_action'] = 'index';
$zen['config']['default_router_action_numeric'] = 'index';

/**
 * rewrite url
 */
$zen['config']['rewrite_url'] = array(

    '/^forum\/(.*)-([0-9]+)\.html(\/*)?$/' => 'forum/index/$2/$1', //forum

    '/^(.*)-([0-9]+)\.html(\/*)?$/' => 'blog/index/$2/$1', //blog

    '/^download-(file|link)-([0-9]+)(-((.*)\.([0-9a-zA-z_\-]+)))?$/' => 'download/$1/$2/$6/$4', //download

    '/^search((\-)?(.*)?)?$/' => 'search/index/$3', //search

    '/^sitemap\.?(xml|html)$/' => 'sitemap/$1' //sitemap
);

/**
 * Module protected
 */
$zen['config']['modules_protected'] = array('admin', 'login', 'register', 'widget');

/**
 * setting user register
 */
$zen['config']['user']['username']['min_length'] = 3; // Min string username
$zen['config']['user']['username']['max_length'] = 30; // Max string username
$zen['config']['user']['password']['min_length'] = 5; // Min string password
$zen['config']['user']['password']['max_length'] = 50; // Max string password

/**
 * setting user permission
 */
$zen['config']['user_perm']['key'] = array(
    'guest' => 0,
    'user_lock' => 1,
    'user_need_active' => 2,
    'user_actived' => 3,
    'mod' => 4,
    'smod' => 5,
    'admin' => 6
);
$zen['config']['user_perm']['name'] = array(
    'guest' => 'Khách',
    'user_lock' => 'Tài khoản đã bị khóa',
    'user_need_active' => 'Tài khoản cần kích hoạt',
    'user_actived' => 'Thành viên bình thường',
    'mod' => 'Mod',
    'smod' => 'Super Mod',
    'admin' => 'Admin'
);
$zen['config']['user_perm']['sign'] = array(
    'guest' => 'Khách',
    'user_lock' => '<s>Member</s>',
    'user_need_active' => 'Member',
    'user_actived' => 'Member!',
    'mod' => 'Mod!',
    'smod' => 'SMod!',
    'admin' => 'Adm!'
);
$zen['config']['user_perm']['color'] = array(
    'guest' => '#555',
    'user_lock' => '#555',
    'user_need_active' => '#555',
    'user_actived' => '#222',
    'mod' => '#00cc00',
    'smod' => '#993399',
    'admin' => '#ff0000'
);
/**
 * set roles for each position
 * For example:
 * With a 'manager' => 'mod', all positions have bigger importance 'mod' are the 'manager'
 */
$zen['config']['role'] = array (
    'manager' => 'mod',
    'super_manager' => 'smod',
    'admin' => 'admin'
);