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

require_once($CFG->libdir . '/form/filepicker.php');
require_once($CFG->libdir . '/form/filemanager.php');

class local_batch_renderer extends plugin_renderer_base {

    private $views = array(
        PARAM_JOB_QUEUE,
        'create_courses',
        'delete_courses',
        'restart_courses',
        'import_courses',
        'config_courses'
    );


    public function print_header($currentview, $category) {
        $tabrow = array();
        foreach ($this->views as $view) {
            $url = $this->url($view, array(PARAM_CATEGORY => $category));
            $tabrow[] = new tabobject($view, $url->out(),
                                      get_string("view_$view", PARAM_LOCAL_BATCH));
        }
        return print_tabs(array($tabrow), $currentview, null, null, true);
    }

    public function url($view=false, $params=array()) {
        if ($view) {
            $params['view'] = $view;
        }
        return new moodle_url('/local/batch/index.php', $params);
    }

    public function print_filter_select($filter) {
        $options = array(
            batch_queue::FILTER_ALL => get_string('filter_all', PARAM_LOCAL_BATCH),
            batch_queue::FILTER_PENDING => get_string('filter_pending', PARAM_LOCAL_BATCH),
            batch_queue::FILTER_FINISHED => get_string('filter_finished', PARAM_LOCAL_BATCH),
            batch_queue::FILTER_ERRORS => get_string('filter_errors', PARAM_LOCAL_BATCH),
            batch_queue::FILTER_PRIORITIZED => get_string('filter_prioritized', PARAM_LOCAL_BATCH),
        );
        return html_writer::select($options, PARAM_FILTER, $filter, '', array('id' => 'local_batch_filter'));
    }

    public function print_table($jobs, $count, $page, $filter, $category) {
        $content = html_writer::start_div('queue-jobs');
        $url = $this->url(PARAM_JOB_QUEUE, array(PARAM_FILTER => $filter,
                                                PARAM_CATEGORY => $category));
        $pagingbar = new paging_bar($count, $page, LOCAL_BATCH_PERPAGE, $url);
        $content .= $this->output->render($pagingbar);
        $table = new html_table();
        $table->id = 'queue-table';
        $table->attributes = array(PARAM_CLASS => 'generaltable');
        $table->head = array('timestarted' => get_string('column_timestarted', PARAM_LOCAL_BATCH),
                         'type' => get_string('column_type', PARAM_LOCAL_BATCH),
                         'params' => get_string('column_params', PARAM_LOCAL_BATCH),
                         'state' => get_string('column_state', PARAM_LOCAL_BATCH),
                         'actions' => get_string('column_action', PARAM_LOCAL_BATCH));

        foreach ($jobs as $job) {
            $action = '';
            $strtype = get_string('type_' . $job->type, PARAM_LOCAL_BATCH);
            $type = batch_type($job->type);
            $job->params->user = $job->user;
            $strparams = $type->params_info($job->params, $job->id);

            $timestarted = $job->timestarted ? strftime("%e %B, %R", $job->timestarted) : '';                    

            if (!$job->timestarted) {
                $state = get_string('state_waiting', PARAM_LOCAL_BATCH);
            } else if (!$job->timeended) {
                $state = get_string('state_executing', PARAM_LOCAL_BATCH);
            } else if ($job->error) {
                if (strlen($job->error) < 30) {
                    $state = get_string('state_error', PARAM_LOCAL_BATCH, $job->error);
                } else {
                    $state = html_writer::start_div('batch_error');
                    $state .= html_writer::tag('span', '', array(PARAM_CLASS => 'batch_error_switcher'));
                    $state .= html_writer::start_tag('span', array(PARAM_CLASS => 'batch_error_message'));
                    $state .= get_string('state_error', PARAM_LOCAL_BATCH, $job->error);
                    $state .= html_writer::end_tag('span');
                    $state .= html_writer::end_div();
                }
            } else {
                $seconds = $job->timeended - $job->timestarted;
                $duration = ($seconds > 60 ? round((float) $seconds / 60) . 'm' : $seconds . 's');
                $state = get_string('state_finished', PARAM_LOCAL_BATCH, $duration);
            }

            $row = new html_table_row();
            if ($job->timestarted == 0) {
                $url = $this->url(false, array('cancel_job' => $job->id,
                                                    PARAM_FILTER => $filter,
                                                    'page' => $page,
                                                    PARAM_SESSKEY => sesskey(),
                                                    PARAM_CATEGORY => $category));
                $action = html_writer::link($url, get_string('cancel', PARAM_LOCAL_BATCH), array(PARAM_TITLE => get_string('cancel', PARAM_LOCAL_BATCH)));
                if (has_capability('moodle/site:config', context_system::instance())) {
                    $customaction = 'prioritize';
                    if ($job->priority) {
                        $customaction = 'desprioritize';
                    }
                    $url = $this->url(false, array($customaction . '_job' => $job->id,
                                                    PARAM_FILTER => $filter,
                                                    'page' => $page,
                                                    PARAM_SESSKEY => sesskey(),
                                                    PARAM_CATEGORY => $category));
                    $action .= html_writer::link($url, get_string($customaction, PARAM_LOCAL_BATCH), array(PARAM_TITLE => get_string($customaction, PARAM_LOCAL_BATCH)));
                }
                if ($job->priority) {
                    $row->attributes = array(PARAM_CLASS => 'priority');
                }
            } else if ($job->timeended > 0 && $job->error) {
                $url = $this->url(false, array('retry_job' => $job->id,
                                                    PARAM_FILTER => $filter,
                                                    'page' => $page,
                                                    PARAM_SESSKEY => sesskey(),
                                                    PARAM_CATEGORY => $category));
                $action = html_writer::link($url, get_string('retry', PARAM_LOCAL_BATCH), array(PARAM_TITLE => get_string('retry', PARAM_LOCAL_BATCH)));
                $row->attributes = array(PARAM_CLASS => 'ko');
            } else {
                $row->attributes = array(PARAM_CLASS => 'ok');
            }
            $row->cells = array($timestarted, $strtype, $strparams, $state, $action);
            $table->data[] = $row;
        }
        if ($table->data) {
            $content .= html_writer::table($table);
        } else {
            $content .= $this->output->heading(get_string('nothingtodisplay'));
        }
        $url = $this->url(PARAM_JOB_QUEUE, array(PARAM_FILTER => $filter,
                                                PARAM_CATEGORY => $category));
        $pagingbar = new paging_bar($count, $page, LOCAL_BATCH_PERPAGE, $url);
        $content .= $this->output->render($pagingbar);
        return $content .= html_writer::end_div();
    }

    public function print_job_queue($jobs, $count, $page, $filter, $category, $totalpending, $mypending) {
        global $CFG;
        $url = $this->url(PARAM_JOB_QUEUE)->out();
        $content = html_writer::start_tag('form', array('id' => 'queue-filter', 'action' => $url));
        $content .= $this->print_filter_select($filter);
        $content .= html_writer::empty_tag(PARAM_INPUT, array('type' => PARAM_HIDDEN, 'name' => PARAM_CATEGORY, PARAM_VALUE => $category));
        if ($totalpending) {
            $content .= html_writer::start_tag('span', array(PARAM_CLASS => 'batch_pending alert alert-info'));
            $content .= get_string('total_pending', PARAM_LOCAL_BATCH, $totalpending) . ' ';
            if ($mypending) {
                $content .= get_string('my_pending', PARAM_LOCAL_BATCH, $mypending);
            } else {
                $content .= get_string('no_my_pending', PARAM_LOCAL_BATCH);
            }
            $content .= html_writer::end_tag('span');
        }
        $starthour = isset($CFG->local_batch_start_hour) ? (int) $CFG->local_batch_start_hour : 0;
        $stophour = isset($CFG->local_batch_stop_hour) ? (int) $CFG->local_batch_stop_hour : 0;
        if ($starthour != $stophour) {
            $content .= html_writer::tag('span', get_string('start_hour', PARAM_LOCAL_BATCH, $starthour), array(PARAM_CLASS => 'batch_starthour alert alert-info'));
        }
        $content .= html_writer::start_tag('noscript');
        $content .= html_writer::empty_tag(PARAM_INPUT, array(
                'type' => PARAM_SUBMIT,
                PARAM_VALUE => get_string('show'),
        ));
        $content .= html_writer::end_tag('noscript');
        $content .= html_writer::end_tag('form');
        return $content .= $this->print_table($jobs, $count, $page, $filter, $category);
    }

    public function config_courses($info, $type_job) {
    
        global $SITE;

        $content = $this->output->container_start($type_job);
        
        $content .= html_writer::start_tag('form', array('id' => 'form', 'method' => 'post'));
        $params = array(
            'id' => PARAM_SESSKEY,
            'type' => PARAM_HIDDEN,
            'name' => PARAM_SESSKEY,
            PARAM_VALUE => sesskey()
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        
        
        if ($type_job != 'batch_config_courses'){
            $params = array(
                'id' => PARAM_CATEGORY,
                'type' => PARAM_HIDDEN,
                'name' => PARAM_CATEGORY,
                PARAM_VALUE => $info[PARAM_CATEGORY]
            );
            $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        }        

        if($type_job == PARAM_BATCH_CREATE_COURSES){        
            $content .= $this->output->container_start(PARAM_SECTION);
            $content .= $this->output->heading(get_string(PARAM_BACKUP), 3);
            $params = array(
                'type' => PARAM_HIDDEN,
                'name' => PARAM_COURSE,
                PARAM_VALUE => $SITE->id
            );
            $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        }
                
        return $content;
    }

    public function print_create_courses($info) {
    
        $content = $this->config_courses($info, PARAM_BATCH_CREATE_COURSES);

        $options = array(
            'accepted_types' => '.mbz',
            'maxfiles' => 1
        );
        $df = new MoodleQuickForm_filemanager('choose-backup', '', array('id' => 'choose-backup'), $options);
        if (isset($info['draftareaid'])) {
            $df->setValue($info['draftareaid']);
        }
        $content .= $df->toHtml();
        $content .= $this->output->container_end(PARAM_SECTION);
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string(PARAM_COURSE.'s'), 3);
        $params = array(
            'type' => PARAM_HIDDEN,
            'name' => 'lastindex',
            PARAM_VALUE => $info['lastindex']
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $table = new html_table();
        $table->id = 'course-list';
        $table->head = array(
            get_string(PARAM_SHORTNAME),
            get_string(PARAM_FULLNAME),
            get_string(PARAM_CATEGORY),
            get_string('action')
        );
        foreach ($info[PARAM_COURSE.'s'] as $i => $course) {
            $params = array(
                'type' => 'text',
                'size' => '16',
                'name' => 'shortname-' . $i,
                PARAM_VALUE => s($course->shortname)
            );
            $cella = html_writer::empty_tag(PARAM_INPUT, $params);
            $params = array(
                'type' => 'text',
                'size' => '48',
                'name' => 'fullname-' . $i,
                PARAM_VALUE => s($course->fullname)
            );
            $cellb = html_writer::empty_tag(PARAM_INPUT, $params);
            $cellc = $this->print_category_menu('category-' . $i, $info[PARAM_CATEGORY], $course->category);
            $celld = html_writer::link('#', get_string('delete'), array(PARAM_CLASS => 'js-only delete-course'));
            $table->data[] = array($cella, $cellb, $cellc, $celld);
        }
        $content .= html_writer::table($table);

        $action_js='actions js-only';

        $content .= $this->output->container_start($action_js);
        $content .= html_writer::link('#', get_string('add'), array('id' => 'add-course'));
        $content .= $this->output->container_end($action_js);

        $content .= $this->output->container_start($action_js);
        $content .= $this->output->heading(get_string('import_from_csv_file', PARAM_LOCAL_BATCH), 4);

        $df = new MoodleQuickForm_filepicker('csvfile', '', array('id' => 'import-csv-file'), array('accepted_types' => '.csv'));
        $content .= $df->toHtml();

        $content .= $this->output->container_end($action_js);
        $content .= $this->output->container_end(PARAM_SECTION);
        $content .= $this->output->container_start(PARAM_SECTION, 'calendar-panel');
        $content .= $this->output->heading(get_string(PARAM_START_DATE, PARAM_LOCAL_BATCH), 3);
        $params = array(
            'id' => PARAM_STARTDATE,
            'type' => 'text',
            'name' => PARAM_STARTDATE,
            PARAM_VALUE => $info[PARAM_STARTDAY] . '/' . $info[PARAM_STARTMONTH] . '/' . $info[PARAM_STARTYEAR]
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $url = $this->output->image_url('i/calendar', 'core');
        $datepicker = html_writer::empty_tag('img', array('id' => PARAM_BATCH_TOGGLE_DATEPICKER, PARAM_CLASS => PARAM_BATCH_TOGGLE_DATEPICKER, 'src' => $url, 'alt' => 'calendar'));
        $content .= html_writer::link('#', $datepicker);
        $content .= $this->output->container_end(PARAM_SECTION);
        $params = array(
            'type' => PARAM_SUBMIT,
            'name' => PARAM_RESTART,
            PARAM_VALUE => get_string(PARAM_ADD_JOBS, PARAM_LOCAL_BATCH)
        );
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end(PARAM_SECTION);
        $content .= html_writer::end_tag('form');
        return $content .= $this->output->container_end(PARAM_BATCH_CREATE_COURSES);
    }

    public function data_info_courses($info, $value, $params, $data_course) {        
        
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string($data_course, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        return $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_CREATOR, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', fullname($params['user']), array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
    }

    public function print_info_create_courses($params) {
        $info = '';
        if (!is_null($params[PARAM_ATTACH])) {
            $iconimage = $this->output->pix_icon(file_file_icon($params[PARAM_ATTACH]), get_mimetype_description($params[PARAM_ATTACH]),
                                        'moodle', array(PARAM_CLASS => 'icon'));
            $info .= html_writer::start_tag('div')
                . html_writer::tag('span', get_string(PARAM_BACKUP), array(PARAM_CLASS => PARAM_BATCH_PARAM))
                . html_writer::start_tag('span', array(PARAM_CLASS => PARAM_BATCH_VALUE))
                . html_writer::link($params['fileurl'], $iconimage) . html_writer::link($params['fileurl'], $params['filename'])
                . html_writer::end_tag('span')
                . html_writer::end_tag('div');
        }
        if (is_int($params[PARAM_COURSEID])) {
            $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_SHORTNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link(new moodle_url(PARAM_URL_COURSE_VIEW, array('id' => $params[PARAM_COURSEID])), $params[PARAM_SHORTNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
            $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_FULLNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link(new moodle_url(PARAM_URL_COURSE_VIEW, array('id' => $params[PARAM_COURSEID])), $params[PARAM_FULLNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        } else {
            $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_SHORTNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $params[PARAM_SHORTNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
            $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_FULLNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $params[PARAM_FULLNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        }
        
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_CATEGORY), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link($params['url'], $params['categoryname'])
            . html_writer::end_tag('div');
        $value = $params[PARAM_STARTDAY] . '/'. $params[PARAM_STARTMONTH] . '/' . $params[PARAM_STARTYEAR];

        return $this->data_info_courses($info, $value, $params, PARAM_START_DATE);
    }

    public function print_delete_courses($courses, $category) {
        $content = $this->output->container_start('batch_delete_courses');
        $content .= html_writer::start_tag('form', array('id' => 'form', 'method' => 'post'));
        $params = array(
            'id' => PARAM_SESSKEY,
            'type' => PARAM_HIDDEN,
            'name' => PARAM_SESSKEY,
            PARAM_VALUE => sesskey()
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $params = array(
            'id' => PARAM_CATEGORY,
            'type' => PARAM_HIDDEN,
            'name' => PARAM_CATEGORY,
            PARAM_VALUE => $category
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string(PARAM_COURSE.'s'), 3);
        $content .= $this->output->container_start('', PARAM_COURSE_TREE);
        $content .= $this->print_course_menu($courses);
        $content .= $this->output->container_end();// close course-tree
        $content .= $this->output->container_end();// close section
        $params = array(
            'type' => PARAM_SUBMIT,
            'name' => PARAM_RESTART,
            PARAM_VALUE => get_string(PARAM_ADD_JOBS, PARAM_LOCAL_BATCH)
        );
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close section
        $content .= html_writer::end_tag('form');
        return $content .= $this->output->container_end();// close batch_delete_courses
    }

    public function print_info_delete_courses($params) {
        $info = html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_SHORTNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link(new moodle_url(PARAM_URL_COURSE_VIEW, array('id' => $params[PARAM_COURSEID])), $params[PARAM_SHORTNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        return $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_CREATOR, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', fullname($params['user']), array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
    }


    public function config_context_courses($content, $info){

        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string('parameters', PARAM_LOCAL_BATCH), 3);
        $content .= $this->output->container_start('', 'calendar-panel');
        $content .= html_writer::label(get_string(PARAM_START_DATE, PARAM_LOCAL_BATCH), PARAM_STARTDATE);
        $params = array(
            'id' => PARAM_STARTDATE,
            'type' => 'text',
            'name' => PARAM_STARTDATE,
            'size' => '10',
            PARAM_VALUE => $info[PARAM_STARTDAY] . '/' . $info[PARAM_STARTMONTH] . '/' . $info[PARAM_STARTYEAR]
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $url = $this->output->image_url('i/calendar', 'core');
        $datepicker = html_writer::empty_tag('img', array('id' => PARAM_BATCH_TOGGLE_DATEPICKER, PARAM_CLASS => PARAM_BATCH_TOGGLE_DATEPICKER, 'src' => $url, 'alt' => 'calendar'));
        $content .= html_writer::link('#', $datepicker);
        $content .= $this->output->container_end();// close startdate
        $content .= $this->output->container_start('batch_category', PARAM_CATEGORY);
        $content .= html_writer::label(get_string('backup_category', PARAM_LOCAL_BATCH), PARAM_CATEGORY);
        $content .= $this->print_category_menu('categorydest', $info[PARAM_CATEGORY]);
        return $content .= $this->output->container_end();// close category
    }

    public function print_restart_courses($courses, $info) {

        $content = $this->config_courses($info, 'batch_restart_courses');

        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string(PARAM_COURSE.'s'), 3);
        $content .= $this->output->container_start('', PARAM_COURSE_TREE);
        $content .= $this->print_course_menu($courses);
        $content .= $this->output->container_end();// close course-tree
        $content .= $this->output->container_end();// close section

        $content = $this->config_context_courses($content, $info);

        $content .= $this->output->container_start('roles');
        $content .= html_writer::label(get_string(PARAM_ROLEASSIGNMENTS, 'role'), PARAM_ROLEASSIGNMENTS);
        $context = context_system::instance();
        $roles = role_get_names($context);
        $content .= html_writer::start_tag('ul', array(PARAM_CLASS => 'batch_assign_roles'));
        foreach ($roles as $role) {
            $content .= html_writer::start_tag('li');
            $content .= html_writer::empty_tag(PARAM_INPUT, array(
                                    'id' => 'role['.$role->id.']',
                                    'type' => PARAM_CHECKBOX,
                                    'name' => 'role[]',
                                    PARAM_VALUE => $role->id
                                    )
                        );
            $content .= html_writer::label($role->localname, 'role['.$role->id.']');
            $content .= html_writer::end_tag('li');
        }
        $content .= html_writer::end_tag('ul');
        $content .= $this->output->container_end();// close roles
        $content .= $this->output->container_start(FEATURE_GROUPS);
        $content .= html_writer::label(get_string('groupsgroupings', 'group'), FEATURE_GROUPS);
        $params = array(
            'id' => FEATURE_GROUPS,
            'type' => PARAM_CHECKBOX,
            'name' => FEATURE_GROUPS,
            'checked' => 'checked'
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close groups
        $content .= $this->output->container_start(PARAM_MATERIALS);
        $content .= html_writer::label(get_string(PARAM_MATERIALS, PARAM_LOCAL_BATCH), PARAM_MATERIALS);
        $params = array(
            'id' => PARAM_MATERIALS,
            'type' => PARAM_CHECKBOX,
            'name' => PARAM_MATERIALS,
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close materials configuration
        $content .= $this->output->container_end();// close section

        $params = array(
            'type' => PARAM_SUBMIT,
            'name' => PARAM_RESTART,
            PARAM_VALUE => get_string(PARAM_ADD_JOBS, PARAM_LOCAL_BATCH)
        );
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close section
        $content .= html_writer::end_tag('form');
        return $content .= $this->output->container_end();// close batch_restart_courses
    }

    public function print_info_restart_courses($params) {
        $info = html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_COURSE), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $params[PARAM_SHORTNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = $params[PARAM_STARTDAY] . '/'. $params[PARAM_STARTMONTH] . '/' . $params[PARAM_STARTYEAR];
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_START_DATE, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        if (is_int($params[PARAM_COURSEID])) {
            $info .= html_writer::start_tag('div')
                . html_writer::tag('span', get_string('course_reset', PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
                . html_writer::link(new moodle_url(PARAM_URL_COURSE_VIEW, array('id' => $params[PARAM_COURSEID])), $params[PARAM_FULLNAME])
                . html_writer::end_tag('div');
        }
        $value = ($params[PARAM_ROLEASSIGNMENTS] ? $params[PARAM_ROLEASSIGNMENTS] : get_string('no'));
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string('roles'), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = ($params[FEATURE_GROUPS] ? get_string('yes') : get_string('no'));
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(FEATURE_GROUPS), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = ($params[PARAM_MATERIALS] ? get_string('yes') : get_string('no'));
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string('materials_short', PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = fullname($params['user']);
        return $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_CREATOR, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
    }

    public function print_import_courses($info) {
        global $CFG;

        $content = $this->config_courses($info, PARAM_BATCH_CREATE_COURSES);
    
        if (!empty($CFG->local_batch_path_backups)) {
            $files = get_directory_list($CFG->dataroot . '/' . $CFG->local_batch_path_backups);

            $filter = function($value) {
                return preg_match('/\.zip$/', $value);
            };

            $files = array_filter($files, $filter);

            $content .= html_writer::start_tag('ul', array(PARAM_CLASS => 'batch_assign_roles'));
            foreach ($files as $key => $file) {
                $params = array(
                    'id' => 'choose-backup[' . $key .']',
                    'name' => 'choose-backup[]',
                    'type' => PARAM_CHECKBOX,
                    PARAM_VALUE => $file
                );
                $content .= html_writer::start_tag('li');
                $content .= html_writer::empty_tag(PARAM_INPUT, $params);
                $content .= html_writer::label(basename($file), 'choose-backup[' . $key . ']');
                $content .= html_writer::end_tag('li');
            }
            $content .= html_writer::end_tag('ul');
        } else {
            $content .= html_writer::tag('div', get_string('nobackupfolder', PARAM_LOCAL_BATCH));
        }

        $content .= $this->output->container_end(PARAM_SECTION);// close backup files section
        
        $content =  $this->config_context_courses($content, $info);
        
        $content .= $this->output->container_start();
        $content .= html_writer::label(get_string('course_display', PARAM_LOCAL_BATCH), PARAM_COURSEDISPLAY);
        $params = array(
            'id' => PARAM_COURSEDISPLAY,
            'type' => PARAM_CHECKBOX,
            'name' => PARAM_COURSEDISPLAY
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();
        $content .= $this->output->container_end(PARAM_SECTION);// close parameters
        $params = array(
            'type' => PARAM_SUBMIT,
            'name' => PARAM_RESTART,
            PARAM_VALUE => get_string(PARAM_ADD_JOBS, PARAM_LOCAL_BATCH)
        );
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close section

        $content .= html_writer::end_tag('form');
        return $content .= $this->output->container_end();// close batch_restore_courses
    }

    public function print_info_import_courses($params) {
        $info = '';
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_BACKUP), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $params['filename'], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        if (is_int($params[PARAM_COURSEID])) {
            $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_FULLNAME), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link(new moodle_url(PARAM_URL_COURSE_VIEW, array('id' => $params[PARAM_COURSEID])), $params[PARAM_FULLNAME], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        }
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_CATEGORY), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::link($params['url'], $params['categoryname'], array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = $params[PARAM_STARTDAY] . '/'. $params[PARAM_STARTMONTH] . '/' . $params[PARAM_STARTYEAR];
        $info .= html_writer::start_tag('div')
            . html_writer::tag('span', get_string(PARAM_START_DATE, PARAM_LOCAL_BATCH), array(PARAM_CLASS => PARAM_BATCH_PARAM))
            . html_writer::tag('span', $value, array(PARAM_CLASS => PARAM_BATCH_VALUE))
            . html_writer::end_tag('div');
        $value = ($params[PARAM_COURSEDISPLAY] ? get_string('yes') : get_string('no'));

        return $this->data_info_courses($info, $value, $params, 'course_display');
        
    }

    public function print_config_courses($courses, $themes) {
        global $CFG;
       
        $content = $this->config_courses(false, 'batch_config_courses');

        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string(PARAM_COURSE.'s'), 3);
        $content .= $this->output->container_start('', PARAM_COURSE_TREE);
        $content .= $this->print_course_menu($courses);
        $content .= $this->output->container_end();// close course-tree
        $content .= $this->output->container_end();// close section
        $content .= $this->output->container_start(PARAM_SECTION);
        $content .= $this->output->heading(get_string('parameters', PARAM_LOCAL_BATCH), 3);
        $content .= $this->output->container_start('course-prefix');
        $content .= html_writer::label(get_string(PARAM_PREFIX, PARAM_LOCAL_BATCH), PARAM_PREFIX);
        $params = array(
            'id'   => PARAM_PREFIX,
            'type' => 'text',
            'name' => PARAM_PREFIX
        );
        $content .= html_writer::tag('span', '[', array(PARAM_CLASS => 'batch_delimiter'));
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= html_writer::tag('span', ']', array(PARAM_CLASS => 'batch_delimiter'));
        $params = array(
            'id'   => PARAM_REMOVE_PREFIX,
            'type' => PARAM_CHECKBOX,
            'name' => PARAM_REMOVE_PREFIX
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= html_writer::label(get_string('remove', PARAM_LOCAL_BATCH), PARAM_REMOVE_PREFIX);
        $content .= $this->output->container_end();// close course-prefix
        $content .= $this->output->container_start('course-suffix');
        $content .= html_writer::label(get_string(PARAM_SUFFIX, PARAM_LOCAL_BATCH), PARAM_SUFFIX);
        $options = array(
            'none'      => get_string('suffix_none', PARAM_LOCAL_BATCH),
            'restarted' => get_string('suffix_restarted', PARAM_LOCAL_BATCH),
            'imported'  => get_string('suffix_imported', PARAM_LOCAL_BATCH)
        );
        $content .= html_writer::select($options, PARAM_SUFFIX, '', array('' => ''), array('id' => PARAM_SUFFIX));
        $content .= $this->output->container_end();// close course-suffix
        $content .= $this->output->container_start('course-visible');
        $content .= html_writer::label(get_string(PARAM_VISIBLE), PARAM_VISIBLE);
        $options = array(
            'yes' => get_string('yes'),
            'no'  => get_string('no')
        );
        $content .= html_writer::select($options, PARAM_VISIBLE, '', array('' => ''), array('id' => PARAM_VISIBLE));
        $content .= $this->output->container_end();// close course-visible
        if (!empty($CFG->allowcoursethemes)) {
            $content .= $this->output->container_start('course-theme');
            $content .= html_writer::label(get_string(PARAM_THEME), PARAM_VISIBLE);
            $content .= html_writer::select($themes, PARAM_THEME, '', array('' => ''), array('id' => PARAM_THEME, PARAM_CLASS => 'batch_theme'));
            $params = array(
                'id'   => PARAM_DEFAULT_THEME,
                'type' => PARAM_CHECKBOX,
                'name' => PARAM_DEFAULT_THEME
            );
            $content .= html_writer::empty_tag(PARAM_INPUT, $params);
            $content .= html_writer::label(get_string(PARAM_DEFAULT_THEME, PARAM_LOCAL_BATCH), PARAM_DEFAULT_THEME);
            $content .= $this->output->container_end();// close course-theme
        }
        $content .= $this->output->container_start(PARAM_SECTION);
        $params = array(
            'type' => PARAM_SUBMIT,
            'name' => 'config',
            PARAM_VALUE => get_string('configure', PARAM_LOCAL_BATCH)
        );
        $content .= html_writer::empty_tag(PARAM_INPUT, $params);
        $content .= $this->output->container_end();// close section
        $content .= $this->output->container_end();// close parameters
        $content .= html_writer::end_tag('form');
        return $content .= $this->output->container_end();// close section
    }

    public function print_category_menu($name="categorydest", $category=0, $selected=0) {
        $cat = batch_get_category($category);
        $categories = core_course_category::make_categories_list('moodle/category:manage', $cat);
        foreach ($categories as $key => $value) {        
            if (core_course_category::get($key)->get_parents() && $indent = count(core_course_category::get($key)->get_parents())) {
                $categories[$key] = str_repeat('&nbsp;', $indent) . $categories[$key];
            }
        }
        return html_writer::select($categories, $name, $selected, array('' => ''), array('id' => $name));
    }

    public function print_course_menu($structure) {
        // Generate an id
        $id = html_writer::random_id('course_category_tree');

        // Start content generation
        $content = html_writer::start_tag('div', array(PARAM_CLASS => 'course_category_tree', 'id' => $id));
        $content .= html_writer::start_tag('ul', array(PARAM_CLASS => 'categories'));
        $categories = core_course_category::make_categories_list('moodle/category:manage');
        foreach ($structure as $category) {
            $content .= $this->make_tree_categories($category, $categories);
        }
        $content .= html_writer::end_tag('ul');
        $content .= html_writer::start_tag('div', array(PARAM_CLASS => 'controls'));
        $content .= html_writer::tag('div', get_string('collapseall'), array(PARAM_CLASS => 'addtoall expandall'));
        $content .= html_writer::tag('div', get_string('expandall'), array(PARAM_CLASS => 'removefromall collapseall'));
        $content .= html_writer::end_tag('div');
        return $content .= html_writer::end_tag('div');
    }

    protected function make_tree_categories($category, $categories) {
        if (!array_key_exists($category->id, $categories)) {
            return false;
        }
        
        $hassubcategories = (isset($category->categories) && count($category->categories) > 0);
        $hascourses = (isset($category->courses) && count($category->courses) > 0);
        $classes = array(PARAM_CATEGORY);
        if ($hassubcategories || $hascourses) {
            $classes[] = 'category_group';
        }
        $content = html_writer::start_tag('li', array(PARAM_CLASS => join(' ', $classes)));
        $content .= html_writer::tag('span', $category->name);
        if ($hassubcategories) {
            $content .= html_writer::start_tag('ul', array(PARAM_CLASS => 'subcategories'));
            foreach ($category->categories as $cat) {
                $content .= $this->make_tree_categories($cat, $categories);
            }
            $content .= html_writer::end_tag('ul');
        }
        if ($hascourses) {
            $url = $this->output->image_url('t/unblock', 'core');
            $params = array (
                'id' => 'batch_toggle_category_' . $category->id,
                PARAM_CLASS => 'batch_toggle_category batch_hidden_toggle',
                'src' => $url, 'alt' => 'toggle',
            );
            $content .= html_writer::empty_tag('img', $params);
            $content .= html_writer::start_tag('ul', array(PARAM_CLASS => PARAM_COURSE.'s'));
            foreach ($category->courses as $course) {
                $content .= html_writer::start_tag('li', array(PARAM_CLASS => PARAM_COURSE));
                $course_param='course-';
                $params = array(
                    'id' => $course_param . $course->id,
                    'name' => $course_param . $course->id,
                    'type' => PARAM_CHECKBOX,
                    PARAM_VALUE => $course->shortname
                );
                $content .= html_writer::empty_tag(PARAM_INPUT, $params);
                $content .= html_writer::label($course->fullname, $course_param . $course->id);
                $content .= html_writer::end_tag('li');
            }
            $content .= html_writer::end_tag('ul');
        }
        return $content .= html_writer::end_tag('li');
    }
}