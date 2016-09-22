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

Class sitemapController Extends ZenController
{
    function index() {
        $model = $this->model->get('sitemap');
        $data['last_update'] = $model->get_last_update();
        if(!isset($_GET['p'])){
			$data['folders'] = $model->get_folders();
        	$data['posts'] = $model->get_posts();
		}else{
			$data['posts'] = $model->get_posts($_GET['p']);
		}
        
        
        $this->view->data = $data;
        $this->view->show('sitemap/index', array('only_map' => true));
    }
    function xml() {
        $model = $this->model->get('sitemap');
        $data['path_sitemap_xsl'] = HOME . '/modules/sitemap/tpl/sitemap.xsl';
        $data['last_update'] = $model->get_last_update();
		$data['folders'] = $model->get_folders();
        
        
        $this->view->data = $data;
        $this->view->show('sitemap/xml', array('only_map' => true));
    }
}