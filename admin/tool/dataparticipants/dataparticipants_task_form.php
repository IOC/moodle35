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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/admin/tool/dataparticipants/locallib.php');

/**
 * Form to manage from which courses will be collected participants data.
 *
 * @package    tool_dataparticipants
 * @copyright  2019 Institut Obert de Catalunya
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class dataparticipants_task_form extends moodleform {

    /**
     * Form definition
     * @return void
     */
    public function definition() {

        $mform = $this->_form;
        $data = $this->_customdata;

        $header = isset($data->id) ? 'edittask' : 'newtask';

        $mform->addElement('header', 'task', get_string($header, PARAM_TOOL_DATAPARTICIPANTS));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $courses = tool_dataparticipants_utils::get_courses();

        $options = array(
            'multiple' => true
        );
        $select = $mform->addElement('autocomplete', PARAM_COURSES, get_string(PARAM_COURSES, PARAM_MOODLE), $courses, $options);
        $mform->addRule(PARAM_COURSES, null, PARAM_REQUIRED, null, PARAM_CLIENT);

        $rolenames = role_fix_names(get_all_roles(), context_system::instance(), ROLENAME_ORIGINAL);

        $rolenames = array_map(function($role) {
            return $role->name;
        }, $rolenames);

        $select = $mform->addElement('autocomplete', PARAM_ROLES, get_string(PARAM_ROLES, PARAM_MOODLE), $rolenames, $options);
        $mform->addRule(PARAM_ROLES, null, PARAM_REQUIRED, null, PARAM_CLIENT);

        $mform->addElement('text', PARAM_EMAIL, get_string(PARAM_EMAIL, PARAM_MOODLE), 'maxlength="100" size="30"');
        $mform->setType(PARAM_EMAIL, PARAM_EMAIL);
        $mform->addRule(PARAM_EMAIL, null, PARAM_REQUIRED, null, PARAM_CLIENT);

        $options = array(
            1 => get_string('weekly', PARAM_TOOL_DATAPARTICIPANTS),
            2 => get_string('quarterly', PARAM_TOOL_DATAPARTICIPANTS),
        );
        $mform->addElement('select', 'scheduled', get_string('interval', PARAM_TOOL_DATAPARTICIPANTS), $options);
        $mform->setType('scheduled', PARAM_INT);

        if (!isset($data->id)) {
            $mform->addElement('checkbox', 'sendnow', get_string('sendnow', PARAM_TOOL_DATAPARTICIPANTS));
        }

        $this->add_action_buttons();

        $this->set_data($data);
    }

    public function get_data() {
        $data = parent::get_data();
        if ($data !== null) {
            $data->courses = implode(',', $data->courses);
            $data->roles = implode(',', $data->roles);
        }
        return $data;
    }
}
