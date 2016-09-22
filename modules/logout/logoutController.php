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

Class logoutController Extends ZenController
{
    function index()
    {
        $model = $this->model->get('logout');
        if (isset($_POST['submit-logout'])) {
            if ($model->logout()) {
                $_get_back = ZenInput::get('back', true);
                if ($_get_back) {
                    $back = urlencode($_get_back);
                } else $back = HOME;
                ZenView::set_success('Thoát thành công!', ZPUBLIC, $back);
            } else ZenView::set_error('Lỗi trong khi thoát!');
        }
        ZenView::set_title('Thoát');
        ZenView::noindex();
        ZenView::set_url(HOME . '/logout');
        $this->view->show('logout');
    }
}