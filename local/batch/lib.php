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
 * @package    local
 * @subpackage batch
 * @copyright  2014 Institut Obert de Catalunya
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/local/batch/locallib.php');

const LOCAL_BATCH_PERPAGE = 20;

define('PARAM_JOB_QUEUE',   'job_queue');
define('PARAM_URL_COURSE_VIEW',   '/course/view.php');
define('PARAM_CATEGORY',   'category');
define('PARAM_LOCAL_BATCH',   'local_batch');
define('PARAM_FILTER',   'filter');
define('PARAM_SESSKEY',   'sesskey');
define('PARAM_TITLE',   'title');
define('PARAM_BATCH_CREATE_COURSES',   'batch_create_courses');
define('PARAM_SECTION',   'section');
define('PARAM_BACKUP',   'backup');
define('PARAM_COURSE',   'course');
define('PARAM_SHORTNAME',   'shortname');
define('PARAM_FULLNAME',   'fullname');
define('PARAM_START_DATE',   'start_date');
define('PARAM_STARTDATE',   'startdate');
define('PARAM_BATCH_TOGGLE_DATEPICKER',   'batch_toggle_datepicker');
define('PARAM_RESTART',   'restart');
define('PARAM_ADD_JOBS',   'add_jobs');
define('PARAM_BATCH_PARAM',   'batch_param');
define('PARAM_BATCH_VALUE',   'batch_value');
define('PARAM_CREATOR',   'creator');
define('PARAM_ATTACH',   'attach');
define('PARAM_COURSEID',   'courseid');
define('PARAM_STARTDAY',   'startday');
define('PARAM_STARTMONTH',   'startmonth');
define('PARAM_STARTYEAR',   'startyear');
define('PARAM_COURSE_TREE',   'course-tree');
define('PARAM_ROLEASSIGNMENTS',   'roleassignments');
define('PARAM_CHECKBOX',   'checkbox');
define('PARAM_MATERIALS',   'materials');
define('PARAM_COURSEDISPLAY',   'coursedisplay');
define('PARAM_PREFIX',   'prefix');
define('PARAM_REMOVE_PREFIX',   'remove_prefix');
define('PARAM_SUFFIX',   'suffix');
define('PARAM_VISIBLE',   'visible');
define('PARAM_DEFAULT_THEME',  'default_theme');
define('PARAM_INPUT',   'input');
define('PARAM_HIDDEN',   'hidden');
define('PARAM_VALUE',   'value');
define('PARAM_SUBMIT',   'submit');
define('PARAM_URL_BATCH',   '/local/batch/index.php');
define('PARAM_PLUGINNAME',   'pluginname');
define('PARAM_CHOOSE_BACKUP',   'choose-backup');
define('PARAM_COURSES',   'courses');
define('PARAM_CATEGORYDEST',   'categorydest');
define('PARAM_TIMESTARTED',   'timestarted');
define('PARAM_TIMEENDED',   'timeended');
define('PARAM_ERROR',   'error');
define('PARAM_LOCAL_BATCH_JOBS',   'local_batch_jobs');
define('PARAM_PRIORITY',   'priority');
define('PARAM_CATEGORY_MOODLE', 'moodle/category:manage');
define('PARAM_LASTINDEX',   'lastindex');
define('PARAM_FILENAME',   'filename');


function local_batch_extend_settings_navigation($nav, $context) {
    if (has_capability('moodle/site:config', context_system::instance())) {
        $node = navigation_node::create(get_string('pluginname', PARAM_LOCAL_BATCH),
                        new moodle_url('/local/batch/index.php',
                        array(PARAM_CATEGORY => 0)),
                        navigation_node::TYPE_ROOTNODE,
                        PARAM_LOCAL_BATCH,
                        PARAM_LOCAL_BATCH,
                        new pix_icon('icon', '', PARAM_LOCAL_BATCH));
        if ($settings = $nav->get('root')) {
            $settings->children->add($node);
        }
    }
    if ($context and has_capability('moodle/category:manage', $context) and $context->contextlevel == CONTEXT_COURSECAT) {
        $node = navigation_node::create(get_string('pluginname', PARAM_LOCAL_BATCH),
                        new moodle_url('/local/batch/index.php',
                        array(PARAM_CATEGORY => $context->instanceid)),
                        navigation_node::TYPE_SETTING,
                        null,
                        null,
                        new pix_icon('icon', '', PARAM_LOCAL_BATCH));
        $settings = $nav->get('categorysettings');
        $settings->children->add($node);
    }
}

function local_batch_pluginfile($context, $filearea, $args, array $options=array()) {
    // Check context

    if (!has_capability('moodle/category:manage', $context)) {
        return false;
    }

    // Check job

    $jobid = (int) array_shift($args);
    $job = batch_queue::get_job($jobid);
    if ($filearea != 'job' or !$job) {
        return false;
    }

    // Fetch file info

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/local_batch/$filearea/$jobid/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, true, $options);
}
