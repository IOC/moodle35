<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * ERROR Jump to ISARD VDI Desktop.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_isardvdi\event;

use core\event\base;

/**
 * ERROR Jump to ISARD VDI Desktop.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class isardvdi_error_jump extends base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['objecttable'] = 'isardvdi';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name(): string {
        return 'ERROR: Jump to Isard VDI Desktop';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description(): string {
        $level = isset($this->other["level"]) ? $this->other["level"] : '';
        $msg = isset($this->other["msg"]) ? $this->other["msg"] : '';

        return "$level: $msg. For userid ('$this->relateduserid')";
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();

        if (!isset($this->context)) {
            throw new \coding_exception('The \'context\' must be set.');
        }

        if (!isset($this->relateduserid)) {
            throw new \coding_exception('The \'relateduserid\' must be set.');
        }

        if (!isset($this->other['level'])) {
            throw new \coding_exception('The \'level\' value must be set in other.');
        }

        if (!isset($this->other['msg'])) {
            throw new \coding_exception('The \'msg\' value must be set in other.');
        }
    }
}
