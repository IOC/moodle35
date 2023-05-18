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
 * Class triggers
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_isardvdi;

use cm_info;
use coding_exception;
use context_course;
use context_module;
use dml_exception;
use mod_isard\api\isard_desktop_api;
use mod_isard\api\response_id;
use mod_isard\api\response_viewer;
use mod_isard\event\isard_desktop_create;
use mod_isard\event\isard_desktop_viewer;
use mod_isard\event\isard_group_create;
use mod_isard\event\isard_template_list;
use mod_isard\event\isard_user_create;
use mod_isard\models\isard_user;
use mod_isardvdi\event\isardvdi_error_jump;
use mod_isardvdi\event\isardvdi_jump;
use moodle_exception;

/**
 * Class triggers
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class triggers {

    /**
     * Jump
     *
     * @param string $token
     * @param array $payload
     * @throws coding_exception
     */
    public static function jump(string $token, array $payload) {
        global $COURSE, $USER;

        $objectid = $COURSE->id;
        $context = context_course::instance($COURSE->id);

        isardvdi_jump::create(array(
            'contextid' => $context->id,
            'objectid' => $objectid,
            'relateduserid' => $USER->id,
            'other' => array('token' => $token, 'payload' => json_encode($payload))))->trigger();
    }

    /**
     * Error Jump
     *
     * @param string $level
     * @param string $msg
     * @throws coding_exception
     */
    public static function jump_error(string $level, string $msg) {
        global $COURSE, $USER;

        $objectid = $COURSE->id;
        $context = context_course::instance($COURSE->id);

        isardvdi_error_jump::create(array(
            'contextid' => $context->id,
            'objectid' => $objectid,
            'relateduserid' => $USER->id,
            'other' => array('level' => $level, 'msg' => $msg)))->trigger();
    }

}
