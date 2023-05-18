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
 * Plugin administration pages are defined here.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading(
        'isardvdi/general',
        get_string('generalheading', 'mod_isardvdi'),
        get_string('generalheadingdesc', 'mod_isardvdi')));

    $settings->add(new admin_setting_configtext(
        'mod_isardvdi/url',
        new lang_string('url', 'mod_isardvdi'),
        '',
        'https://demo.isardvdi.com', PARAM_URL, 70
    ));

    $settings->add(new admin_setting_configtext(
        'mod_isardvdi/kid',
        new lang_string('kid', 'mod_isardvdi'),
        '',
        'moodle-ioc', PARAM_TEXT, 70
    ));

    $settings->add(new admin_setting_configpasswordunmask(
        'mod_isardvdi/secret',
        new lang_string('secret', 'mod_isardvdi'),
        '',
        ''
    ));

    $settings->add(new admin_setting_configduration(
        'mod_isardvdi/exp',
        new lang_string('exp', 'mod_isardvdi'),
        new lang_string('exp_desc', 'mod_isardvdi'),
        600, 60
    ));

    $settings->add(new admin_setting_heading(
        'isardvdi/maprole',
        get_string('maproleheading', 'mod_isardvdi'),
        get_string('maproleheadingdesc', 'mod_isardvdi')));

    $settings->add(new admin_setting_pickroles('mod_isardvdi/advancedrole',
        new lang_string('advanced', 'mod_isardvdi'),
        new lang_string('advanceddesc', 'mod_isardvdi'),
        array('editingteacher')));

    $settings->add(new admin_setting_pickroles('mod_isardvdi/userrole',
        new lang_string('userrole', 'mod_isardvdi'),
        new lang_string('userroledesc', 'mod_isardvdi'),
        array('student')));


    $settings->add(new admin_setting_heading(
        'isardvdi/other',
        get_string('otherheading', 'mod_isardvdi'),
       ''));

    $settings->add(new admin_setting_configcheckbox('mod_isardvdi/logtoken',
        new lang_string('logtoken', 'mod_isardvdi'),
        new lang_string('logtokendesc', 'mod_isardvdi'),
        false
    ));

}
