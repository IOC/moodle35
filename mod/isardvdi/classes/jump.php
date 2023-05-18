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
 * Jump to Isard VDI
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_isardvdi;

use cm_info;
use context_course;
use core_user;
use dml_exception;
use Firebase\JWT\JWT;
use moodle_exception;
use stdClass;

/**
 * Jump to Isard VDI
 *
 * @package    mod_isardvdi
 * @copyright  2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class jump {

    /** @var int User ID  */
    protected $userid;

    /** @var cm_info Course Module */
    protected $cm;

    /** @var stdClass Course */
    protected $course;

    /** @var string URL */
    protected $url;

    /** @var string KID */
    protected $kid;

    /** @var string Secret */
    protected $secret;

    /** @var int Expiration Time */
    protected $exp;

    /**
     * constructor.
     *
     * @param stdClass $course
     * @param cm_info $cm
     * @param int|null $userid
     * @throws dml_exception
     */
    public function __construct(stdClass $course, cm_info $cm, int $userid = null) {
        global $USER;
        $this->userid = (is_null($userid)) ? $USER->id : $userid;
        $this->cm = $cm;
        $this->course = $course;
        $this->url = get_config('mod_isardvdi', 'url');
        $this->kid = get_config('mod_isardvdi', 'kid');
        $this->secret = get_config('mod_isardvdi', 'secret');
        $this->exp = get_config('mod_isardvdi', 'exp');
    }


    /**
     * Get Token.
     *
     * @return string
     * @throws moodle_exception
     * @throws \Exception
     */
    public function get_token(): string {
        return JWT::encode($this->get_payload(), $this->secret, 'HS256');
    }

    /**
     * Get Payload.
     *
     * @param int|null $time
     * @return array
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function get_payload(int $time = null): array {
        $user = core_user::get_user($this->userid);
        $rolesmoodle = $this->get_roles_in_course($this->course);
        $roleisard = $this->get_role_map_in_isard($rolesmoodle);

        $time = is_null($time) ? time() : $time;

        if (empty($this->exp)) {
            $exp = $time + 600;
        } else {
            $exp = $time + (int)$this->exp;
        }
        return [
            'iat' => $time,
            'exp' => $exp,
            'kid' => $this->kid,
            'iss' => 'external-app-' . $this->kid,
            'type' => 'external',
            'user_id' => $this->userid,
            'group_id' => $this->course->id,
            'role' => $roleisard,
            'username' => $user->username,
            'email' => $user->email,
            'name' => $user->firstname . ' ' . $user->lastname,
            'external_app_data' => [
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'cm' => [
                    'cmid' => $this->cm->id,
                    'name' => $this->cm->name,
                    'description' => $this->get_intro(),
                ],
                'course' => [
                    'course_id' => $this->course->id,
                    'shortname' => $this->course->shortname,
                    'fullname' => $this->course->fullname,
                ],
                'role' => [
                    'moodle' => $rolesmoodle,
                    'isard' => $roleisard
                ],
            ],
            'action' => 'jump'
        ];
    }

    /**
     * Get URL redirect.
     *
     * @return string
     * @throws moodle_exception
     */
    public function get_redirect_url(): string {
        $parameters = [
            'provider' => 'external',
            'redirect' => "/desktops",
            'token' => $this->get_token()
        ];
        return $this->url . '/authentication/login?' . http_build_query($parameters);
    }

    /**
     * Get Intro.
     *
     * @return string
     * @throws dml_exception
     */
    protected function get_intro(): string {
        global $DB;
        $instance = $DB->get_record($this->cm->modname, ['id' => $this->cm->instance]);
        if (isset($instance)) {
            return $instance->intro;
        } else {
            return '';
        }
    }

    /**
     * Get role in course.
     *
     * @param stdClass $course
     * @return string[]
     * @throws moodle_exception
     */
    protected function get_roles_in_course(stdClass $course): array {
        $context = context_course::instance($course->id);
        $roles = get_user_roles($context, $this->userid);
        if (empty($roles)) {
            if (is_siteadmin()) {
                return [];
            } else {
                throw new moodle_exception(get_string('error_is_not_enrol', 'mod_isardvdi'));
            }
        } else {
            $rs = [];
            foreach ($roles as $role) {
                $rs[] = $role->shortname;
            }
            return $rs;
        }
    }

    /**
     * Get Role map in Isard VDI.
     *
     * @param string[] $roles
     * @return string
     * @throws dml_exception
     * @throws moodle_exception
     */
    protected function get_role_map_in_isard(array $roles): string {
        global $DB;
        $advancedrole = explode(',', get_config('mod_isardvdi', 'advancedrole'));
        $userrole = explode(',', get_config('mod_isardvdi', 'userrole'));

        foreach ($roles as $role) {
            $roledb = $DB->get_record('role', array('shortname' => $role));
            if (in_array($roledb->id, $advancedrole)) {
                return 'advanced';
            }
        }

        foreach ($roles as $role) {
            $roledb = $DB->get_record('role', array('shortname' => $role));
            if (in_array($roledb->id, $userrole)) {
                return 'user';
            }
        }

        if (is_siteadmin()) {
            return '';
        } else {
            throw new moodle_exception(get_string('error_not_role_valid', 'mod_isardvdi'));
        }
    }

}
