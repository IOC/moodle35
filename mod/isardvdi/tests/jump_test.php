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

namespace mod_isardvdi;

use externallib_advanced_testcase;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/tests/helpers.php');
require_once($CFG->libdir . '/filelib.php');

/**
 * Tests.
 *
 * @package    mod_isardvdi
 * @copyright  2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @coversDefaultClass \mod_isardvdi\jump
 */
class jump_test extends externallib_advanced_testcase {

    /** @var string KID */
    const KID = 'moodle-ioc-test';

    /** @var string SECRET */
    const SECRET = '1234acbsDSFD';

    /** @var int EXP */
    const EXP = 600;

    /** @var string Role Teacher */
    const ROLE_TEACHER = 'editingteacher';

    /** @var string Role Student */
    const ROLE_STUDENT = 'student';

    /** @var string New Role */
    const ROLE_NEWROLE = 'newrole';

    /** @var stdClass Course */
    protected $course;

    /** @var stdClass Teacher User */
    protected $teacher;

    /** @var stdClass Student User */
    protected $student;

    /** @var stdClass Teacher&Student User */
    protected $teacherstudent;

    /** @var stdClass New Role User */
    protected $usernewrole;

    /** @var cm_info Course Module Isard VDI */
    protected $isardvdi;

    /** @var stdClass Role Teacher */
    protected $roleteacher;

    /** @var stdClass Role Student */
    protected $rolestudent;

    /** @var stdClass Role new */
    protected $rolenew;

    /**
     * Tests Set UP.
     *
     * @throws dml_exception|coding_exception
     */
    public function setUp():void {
        global $DB;

        // Create New Role and set configuration.
        $rr = [
            'shortname' => self::ROLE_NEWROLE
        ];
        $this->getDataGenerator()->create_role($rr);

        $this->roleteacher = $DB->get_record('role', array('shortname' => self::ROLE_TEACHER));
        $this->rolestudent = $DB->get_record('role', array('shortname' => self::ROLE_STUDENT));
        $this->rolenew = $DB->get_record('role', array('shortname' => self::ROLE_NEWROLE));

        set_config('url', 'https://demo.isardvdi.com', 'mod_isardvdi');
        set_config('kid', self::KID, 'mod_isardvdi');
        set_config('secret', self::SECRET, 'mod_isardvdi');
        set_config('exp', self::EXP, 'mod_isardvdi');
        set_config('advancedrole', $this->roleteacher->id, 'mod_isardvdi');
        set_config('userrole', $this->rolestudent->id, 'mod_isardvdi');

        // Create Course.
        $cr = [
            'fullname' => 'PHP Unit Course 1',
            'shortname' => 'PHPUnitCourse1'
        ];
        $this->course = $this->getDataGenerator()->create_course($cr);

        // Create User Teacher.
        $tr = [
            'firstname' => 'Teacher 1',
            'lastname' => 'PHP Unit',
            'username' => 'teacher1',
            'email' => 'teacher1@test.xxx',
        ];
        $this->teacher = $this->getDataGenerator()->create_user($tr);

        // Create User Student.
        $sr = [
            'firstname' => 'Student 1',
            'lastname' => 'PHP Unit',
            'username' => 'student1',
            'email' => 'student1@test.xxx',
        ];
        $this->student = $this->getDataGenerator()->create_user($sr);

        // Create User Teacher&Student.
        $tsr = [
            'firstname' => 'Teacher&Student 1',
            'lastname' => 'PHP Unit',
            'username' => 'teacherstudent1',
            'email' => 'teacherstudent1@test.xxx',
        ];
        $this->teacherstudent = $this->getDataGenerator()->create_user($tsr);

        // Create User NewRole.
        $nrr = [
            'firstname' => 'NewRole 1',
            'lastname' => 'PHP Unit',
            'username' => 'newrole1',
            'email' => 'newrole1@test.xxx',
        ];
        $this->usernewrole = $this->getDataGenerator()->create_user($nrr);

        // Enrol Teacher.
        $this->getDataGenerator()->enrol_user(
            $this->teacher->id, $this->course->id, self::ROLE_TEACHER, 'manual');

        // Enrol Student.
        $this->getDataGenerator()->enrol_user(
            $this->student->id, $this->course->id, self::ROLE_STUDENT, 'manual');

        // Enrol Teacher&Student.
        $this->getDataGenerator()->enrol_user(
            $this->teacherstudent->id, $this->course->id, self::ROLE_TEACHER, 'manual');
        $this->getDataGenerator()->enrol_user(
            $this->teacherstudent->id, $this->course->id, self::ROLE_STUDENT, 'manual');

        // Enrol NewRole.
        $this->getDataGenerator()->enrol_user(
            $this->usernewrole->id, $this->course->id, self::ROLE_NEWROLE, 'manual');

        // 8. Create Course Module.
        /** @var mod_isardvdi_generator $isardvdigenerator */
        $isardvdigenerator = $this->getDataGenerator()->get_plugin_generator('mod_isardvdi');
        $isardvdi = $isardvdigenerator->create_instance(
            [
                'course' => $this->course->id,
                'name' => 'Test PHP Unit 1',
                'intro' => 'Description 1'
            ]
        );

        try {
            list($this->course, $this->isardvdi) = get_course_and_cm_from_cmid($isardvdi->cmid);
        } catch (moodle_exception $e) {
            debugging($e->getMessage());
        }

        $this->resetAfterTest(true);
    }

    /**
     * Tests get_payload.
     *
     * @covers ::get_payload
     * @throws coding_exception
     */
    public function test_get_payload() {

        $time = time();

        $payloadteacher = null;
        $payloadstudent = null;
        $payloadteacherstudent = null;

        try {
            $jumpteacher = new jump($this->course, $this->isardvdi, $this->teacher->id);
            $payloadteacher = $jumpteacher->get_payload($time);
            $jumpstudent = new jump($this->course, $this->isardvdi, $this->student->id);
            $payloadstudent = $jumpstudent->get_payload($time);
            $jumpteacherstudent = new jump($this->course, $this->isardvdi, $this->teacherstudent->id);
            $payloadteacherstudent = $jumpteacherstudent->get_payload($time);
        } catch (moodle_exception $e) {
            debugging($e->getMessage());
        }

        $payloadteachertest = [
            'iat' => $time,
            'exp' => $time + self::EXP,
            'kid' => self::KID,
            'iss' => 'external-app-' . self::KID,
            'type' => 'external',
            'user_id' => $this->teacher->id,
            'group_id' => $this->course->id,
            'role' => 'advanced',
            'username' => 'teacher1',
            'email' => 'teacher1@test.xxx',
            'name' => 'Teacher 1 PHP Unit',
            'external_app_data' => [
                'username' => 'teacher1',
                'email' => 'teacher1@test.xxx',
                'firstname' => 'Teacher 1',
                'lastname' => 'PHP Unit',
                'cm' => [
                    'cmid' => $this->isardvdi->id,
                    'name' => 'Test PHP Unit 1',
                    'description' => 'Description 1',
                ],
                'course' => [
                    'course_id' => $this->course->id,
                    'shortname' => 'PHPUnitCourse1',
                    'fullname' => 'PHP Unit Course 1',
                ],
                'role' => [
                    'moodle' => [self::ROLE_TEACHER],
                    'isard' => 'advanced'
                ],
            ],
            'action' => 'jump'
        ];

        $payloadstudenttest = [
            'iat' => $time,
            'exp' => $time + self::EXP,
            'kid' => self::KID,
            'iss' => 'external-app-' . self::KID,
            'type' => 'external',
            'user_id' => $this->student->id,
            'group_id' => $this->course->id,
            'role' => 'user',
            'username' => 'student1',
            'email' => 'student1@test.xxx',
            'name' => 'Student 1 PHP Unit',
            'external_app_data' => [
                'username' => 'student1',
                'email' => 'student1@test.xxx',
                'firstname' => 'Student 1',
                'lastname' => 'PHP Unit',
                'cm' => [
                    'cmid' => $this->isardvdi->id,
                    'name' => 'Test PHP Unit 1',
                    'description' => 'Description 1',
                ],
                'course' => [
                    'course_id' => $this->course->id,
                    'shortname' => 'PHPUnitCourse1',
                    'fullname' => 'PHP Unit Course 1',
                ],
                'role' => [
                    'moodle' => [self::ROLE_STUDENT],
                    'isard' => 'user'
                ],
            ],
            'action' => 'jump'
        ];

        $payloadteacherstudenttest = [
            'iat' => $time,
            'exp' => $time + self::EXP,
            'kid' => self::KID,
            'iss' => 'external-app-' . self::KID,
            'type' => 'external',
            'user_id' => $this->teacherstudent->id,
            'group_id' => $this->course->id,
            'role' => 'advanced',
            'username' => 'teacherstudent1',
            'email' => 'teacherstudent1@test.xxx',
            'name' => 'Teacher&Student 1 PHP Unit',
            'external_app_data' => [
                'username' => 'teacherstudent1',
                'email' => 'teacherstudent1@test.xxx',
                'firstname' => 'Teacher&Student 1',
                'lastname' => 'PHP Unit',
                'cm' => [
                    'cmid' => $this->isardvdi->id,
                    'name' => 'Test PHP Unit 1',
                    'description' => 'Description 1',
                ],
                'course' => [
                    'course_id' => $this->course->id,
                    'shortname' => 'PHPUnitCourse1',
                    'fullname' => 'PHP Unit Course 1',
                ],
                'role' => [
                    'moodle' => [self::ROLE_TEACHER, self::ROLE_STUDENT],
                    'isard' => 'advanced'
                ],
            ],
            'action' => 'jump'
        ];

        $this->assertEquals($payloadteachertest, $payloadteacher);
        $this->assertEquals($payloadstudenttest, $payloadstudent);
        $this->assertEquals($payloadteacherstudenttest, $payloadteacherstudent);

        $roles = [$this->roleteacher->id, $this->rolenew->id];
        $roles = implode(',', $roles);
        set_config('advancedrole', $roles, 'mod_isardvdi');

        $payloadnewrole = null;

        try {
            $jumpnewrole = new jump($this->course, $this->isardvdi, $this->usernewrole->id);
            $payloadnewrole = $jumpnewrole->get_payload($time);
        } catch (moodle_exception $e) {
            debugging($e->getMessage());
        }

        $payloadnewroletest = [
            'iat' => $time,
            'exp' => $time + self::EXP,
            'kid' => self::KID,
            'iss' => 'external-app-' . self::KID,
            'type' => 'external',
            'user_id' => $this->usernewrole->id,
            'group_id' => $this->course->id,
            'role' => 'advanced',
            'username' => 'newrole1',
            'email' => 'newrole1@test.xxx',
            'name' => 'NewRole 1 PHP Unit',
            'external_app_data' => [
                'username' => 'newrole1',
                'email' => 'newrole1@test.xxx',
                'firstname' => 'NewRole 1',
                'lastname' => 'PHP Unit',
                'cm' => [
                    'cmid' => $this->isardvdi->id,
                    'name' => 'Test PHP Unit 1',
                    'description' => 'Description 1',
                ],
                'course' => [
                    'course_id' => $this->course->id,
                    'shortname' => 'PHPUnitCourse1',
                    'fullname' => 'PHP Unit Course 1',
                ],
                'role' => [
                    'moodle' => [self::ROLE_NEWROLE],
                    'isard' => 'advanced'
                ],
            ],
            'action' => 'jump'
        ];

        $this->assertEquals($payloadnewroletest, $payloadnewrole);

        $roles = [$this->roleteacher->id];
        $roles = implode(',', $roles);
        set_config('advancedrole', $roles, 'mod_isardvdi');

        $roles = [$this->rolestudent->id, $this->rolenew->id];
        $roles = implode(',', $roles);
        set_config('userrole', $roles, 'mod_isardvdi');

        $payloadnewrole = null;

        try {
            $jumpnewrole = new jump($this->course, $this->isardvdi, $this->usernewrole->id);
            $payloadnewrole = $jumpnewrole->get_payload($time);
        } catch (moodle_exception $e) {
            debugging($e->getMessage());
        }

        $payloadnewroletest['role'] = 'user';
        $payloadnewroletest['external_app_data']['role']['moodle'] = [self::ROLE_NEWROLE];
        $payloadnewroletest['external_app_data']['role']['isard'] = 'user';

        $this->assertEquals($payloadnewroletest, $payloadnewrole);

    }

}
