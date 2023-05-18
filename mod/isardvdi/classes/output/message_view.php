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
 * Class message_view
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_isardvdi\output;

use cm_info;
use mod_isardvdi\jump;
use moodle_exception;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Class message_view
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class message_view implements renderable, templatable {

    /** @var string  */
    protected $msg;

    /** @var string  */
    protected $level;

    /**
     * teacher_view constructor.
     *
     * @param string $msg
     * @param string $level
     */
    public function __construct(string $msg, string $level = \core\output\notification::NOTIFY_INFO) {
        $this->msg = $msg;
        $this->level = $level;
    }

    /**
     * Export for template
     *
     * @param renderer_base $output
     * @return false|stdClass|string
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->msg = $this->msg;
        $data->level = $this->level;
        return $data;
    }

}
