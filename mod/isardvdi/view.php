<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_isard.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_isardvdi\event\course_module_viewed;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

global $DB, $PAGE, $OUTPUT;

// Course_module ID, or.
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$t = optional_param('t', 0, PARAM_INT);
$u = optional_param('u', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id(
        'isardvdi', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record(
        'course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record(
        'isardvdi', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($t) {
    $moduleinstance = $DB->get_record(
        'isardvdi', array('id' => $t), '*', MUST_EXIST);
    $course = $DB->get_record(
        'course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance(
        'isardvdi', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    debugging(get_string('missingidandcmid', 'mod_isardvdi'));
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('isardvdi', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/isardvdi/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

$PAGE->requires->css('/mod/isardvdi/styles.css');

try {
    if (is_siteadmin()) {
        echo $OUTPUT->header();
        $output = $PAGE->get_renderer('mod_isardvdi');
        $msg = get_string('error_is_admin', 'mod_isardvdi');
        $page = new \mod_isardvdi\output\message_view($msg, 'info');
        echo $output->render($page);
        echo $OUTPUT->footer();
    } else {
        list($course, $cminfo) = get_course_and_cm_from_cmid($cm->id);
        $jump = new \mod_isardvdi\jump($course, $cminfo);
        \mod_isardvdi\triggers::jump($jump->get_token(), $jump->get_payload());
        redirect($jump->get_redirect_url());
    }
} catch (\Exception $e) {
    try {
        echo $OUTPUT->header();
        $output = $PAGE->get_renderer('mod_isardvdi');
        $page = new \mod_isardvdi\output\message_view($e->getMessage(), 'danger');
        \mod_isardvdi\triggers::jump_error('ERROR', $e->getMessage());
        echo $output->render($page);
        echo $OUTPUT->footer();
    } catch (Exception $e) {
        debugging($e->getMessage());
    }
}

