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
 * Jump to ISARD VDI Desktop.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_isardvdi\event;

use core\event\base;

/**
 * Jump to ISARD VDI Desktop.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class isardvdi_jump extends base {

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
        return 'Jump to Isard VDI Desktop';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     * @throws \dml_exception
     */
    public function get_description(): string {

        $token = isset($this->other["token"]) ? $this->other["token"] : '';

        $msg = "The userid ('$this->relateduserid') JUMP to Isard VDI Desktop.";

        if (get_config('mod_isardvdi', 'logtoken')) {
            $msg .= "The userid ('$this->relateduserid') JUMP to Isard VDI Desktop. </br>" .
            "<span style='display: block; max-width: 350px; font-size: 8px; overflow-wrap: break-word;'>$token</span>";
        }

        return $msg;
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

        if (!isset($this->other['token'])) {
            throw new \coding_exception('The \'token\' value must be set in other.');
        }

        if (!isset($this->other['payload'])) {
            throw new \coding_exception('The \'payload\' value must be set in other.');
        }
    }
}
