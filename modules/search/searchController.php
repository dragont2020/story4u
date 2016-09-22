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

Class searchController Extends ZenController
{

    function index($arg = array()) {
        /**
         * load gadget
         */
        load_helper('gadget');
        /**
         * get blog model
         */
        $model = $this->model->get('search');
        $hook = $this->hook->get('search');
        /**
         * Load library
         */
        $security = load_library('security');
        $p = load_library('pagination');
        $seo = load_library('seo');
        ZenView::add_js(_URL_MODULES . '/search/js/search.js', 'foot');

        ZenView::append_head(gadget_search_push());

        $data['search_pagination'] = '';
        $data['result'] = $model->db->query("SELECT * FROM " . tb() . "blogs WHERE  parent = 0 AND type = 'folder' AND status = 0 ORDER BY time");

        ZenView::set_title('Tìm kiếm');
        ZenView::set_breadcrumb(url(HOME . '/search', 'Tìm kiếm'));

        if (empty($arg[0]) || !isset($arg[0])) {
            $this->view->data = $data;
            $this->view->show('search');
            return;
        }
    }
}