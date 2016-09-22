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

Class widgetModel Extends ZenModel
{
    public function get_widget_callback($name) {
        $class = $name . 'Widget';
        /**
         * set model path
         */
        $file = __MODULES_PATH . '/' . $name . '/' . $class . ".php";

        /**
         * make sure file exists
         */
        if (file_exists($file)) {
            include_once $file;
            $output = NULL;
            if (class_exists($class, false)) {
                /**
                 * initialize model
                 */
                $output = new $class($this->registry);
            }
            return $output;
        }
        return NULL;
    }

    /**
     * Check a widget is exists
     * @param int $id
     * @return bool
     */
    public function widget_exists($id) {
        $sql = "SELECT `id` FROM ".tb()."widgets WHERE `id`='$id'";
        $query = $this->db->query($sql);
        if ($this->db->num_row($query)) return true;
        else return false;
    }

    /**
     * Get widget in database
     * @param string $template
     * @return array
     */
    public function list_template_widget_group($template) {
        $out = array();
        $_sql = "SELECT * FROM ".tb()."widgets WHERE `template` = '$template' order by `weight` ASC";
        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            $row = $this->db->sqlQuoteRm($row);
            if (empty($out[$row['wg']])) $out[$row['wg']][] = $row;
            else $out[$row['wg']] = array_merge($out[$row['wg']], array($row));
        }
        return $out;
    }

    /**
     * get widget data by ids widget
     * @param $wi
     * @return mixed
     */
    public function get_widget_data($wi = false) {
        $_sql = "SELECT * FROM ".tb()."widgets where `id` = '$wi'";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;//return data if cache is exists
        $query = $this->db->query($_sql);
        $row = $this->db->fetch_array($query);
        $row = $this->db->sqlQuoteRm($row);
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $row, 7200);
        return $row;
    }

    /**
     * Update widget data
     * @param int $wid
     * @param array $data
     * @return bool
     */
    public function update_widget($wid, $data) {
        $data = $this->db->sqlQuote($data);
        $_sql = $this->db->_sql_update(tb().'widgets', $data, "id = '$wid'");
        if ($this->db->query($_sql)) {
            ZenCaching::clean('get_widget_data');
            ZenCaching::clean('get_widget_group', 'widgetModel');
            return true;
        }
        return false;
    }

    /**
     * Insert new widget to database
     * @param array $data
     * @return bool
     */
    public function insert_widget($data) {
        if(empty($data['content'])) return false;
        $data = $this->db->sqlQuote($data);
        $_sql = $this->db->_sql_insert(tb().'widgets', $data);
        if ($this->db->query($_sql)) {
            ZenCaching::clean('get_widget_data');
            ZenCaching::clean('get_widget_group', 'widgetModel');
            return true;
        }
        return false;
    }

    /**
     * Delete a widget by id
     * @param int $wid
     * @return bool
     */
    public function delete_widget($wid) {
        $_sql = "DELETE FROM ".tb()."widgets WHERE `id` = '$wid'";
        if ($this->db->query($_sql)) {
            ZenCaching::clean('get_widget_data');
            ZenCaching::clean('get_widget_group', 'widgetModel');
            return true;
        }
        return false;
    }

    /**
     * get all widget of a widget group
     * @param $wg
     * @param string $template
     * @return array
     */
    public function get_widget_group($wg, $template = '') {
        if (empty($template)) {
            $template = TEMPLATE;
        }
        $out = array();
        if (!defined('MODULE_NAME')) {
            return $out;
        }
        $_sql = "SELECT * FROM ".tb()."widgets WHERE `wg` = '$wg' AND `template` = '$template' ORDER BY `weight` ASC";
        /**
         * get cache
         */
        $cache = ZenCaching::get($_sql);
        if ($cache != null) return $cache;//return data if cache is exists
        $query = $this->db->query($_sql);
        while ($row = $this->db->fetch_array($query)) {
            $out[] = $this->db->sqlQuoteRm($row);
        }
        /**
         * set the new cache
         */
        ZenCaching::set($_sql, $out, 7200);
        return $out;
    }
}
