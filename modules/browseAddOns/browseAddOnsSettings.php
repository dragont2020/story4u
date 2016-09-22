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

Class browseAddOnsSettings Extends ZenSettings
{
    public function __construct()
    {
        $this->setting['filter_access'] = array(
            'index' => 'admin',
            'module' => 'admin',
            'template' => 'admin',
            'install' => 'admin',
            'purchase' => 'admin',
            'checkUpdate' => 'admin',
            'ajax_check_update' => 'admin'
        );
        $this->setting['extends'] = array(
            'index' => array(
                'router' => 'admin/general/modulescp',
                'name' => 'Tìm kiếm Add-ons mới'
            )
        );
        $this->setting['run'] = array(
            'appendMenuControls' => array(
                'admin/general/modules/',
                'admin/general/templates/'
            ),
            'update_notice' => 'admin/'
        );
    }
}