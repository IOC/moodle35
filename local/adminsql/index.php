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
 * @package    local-adminsql
 * @author     Marc Català <reskit@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/local/adminsql/locallib.php');
require_once($CFG->dirroot . '/local/adminsql/form.php');


admin_externalpage_setup('local_adminsql');

require_capability('moodle/site:config', context_system::instance());

$PAGE->requires->css('/local/adminsql/styles.css');

$adminsqloutput = $PAGE->get_renderer('local_adminsql');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('adminsql', 'local_adminsql'));

$mform = new adminsql_form();

$result = false;
if ($data = $mform->get_data()) {
    $result = process_sql($data);
} else {
    $eventdata = array();
    $eventdata['context'] = context_system::instance();
    $eventdata['userid'] = $USER->id;

    $event = \local_adminsql\event\access_adminsql::create($eventdata);
    $event->trigger();
}

$mform->display();
if ($result) {
    echo $adminsqloutput->sql_result($result);
}

echo $OUTPUT->footer();
