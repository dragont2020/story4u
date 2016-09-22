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
ZenView::section('Cài đặt tài khoản', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block('Cài đặt smile', function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                echo '<form method="POST">';
                echo '<ul class="list-group">';
                foreach (ZenView::$D['smiles'] as $smile) {
                    echo '<li class="list-group-item title">
                    <label for="smile-' . $smile['key'] . '">
                    ' . (!$smile['added']?'<input type="checkbox" name="smile[]" id="smile-' . $smile['key'] . '" value="' . $smile['key'] . '"/>':'') . '
                    <img src="' . $smile['full_url'] . '"/> ' . $smile['name'] . '
                    </label>
                    </li>';
                }
                echo '</ul>';
                ZenView::display_paging();
                echo '<div class="row"><div class="col-lg-12"><span class="pull-right footer-action"><input type="submit" name="submit-add" value="Thêm" class="btn btn-primary"/></span><div class="clearfix"></div></div></div>';
                echo '</form>';
            });
        });
        ZenView::col_item(3, function() {
            $pageMenu = ZenView::get_menu('page');
            if (isset($pageMenu['name'])) ZenView::block($pageMenu['name'], function() use ($pageMenu) {
                echo '<ul class="list-group">';
                foreach ($pageMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });

            $objMenu = ZenView::get_menu('main');
            ZenView::block($objMenu['name'], function() use ($objMenu) {
                echo '<ul class="list-group">';
                foreach ($objMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });
        });
    });
});