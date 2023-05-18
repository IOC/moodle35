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
 * Plugin strings are defined here 'en'.
 *
 * @package     mod_isardvdi
 * @category    string
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Isard VDI Desktops';
$string['modulename'] = 'Isard VDI Desktops';
$string['modulenameplural'] = 'Isard VDI Desktops';
$string['missingidandcmid'] = 'Course module not found';
$string['nonewmodules'] = 'No New Modules';
$string['pluginadministration'] = 'Isard VDI Desktops administration';
$string['isardvdi:addinstance'] = 'Add instance Isard VDI Desktops';
$string['isardvdi:view'] = 'View Isard Desktop';
$string['view_desktop'] = 'Isard VDI Desktop';
$string['isardvdi:grade'] = 'Grade Isard VDI Desktops';
$string['isardvdi:viewgrades'] = 'View Grades Isard VDI Desktops';
$string['generalheading'] = 'General Description';
$string['generalheadingdesc'] = 'General settings for the plugin operation';
$string['url'] = 'URL Isard VDI Desktops';
$string['kid'] = 'KEY Id';
$string['secret'] = 'Secret';
$string['maproleheading'] = 'Role mapping';
$string['maproleheadingdesc'] = 'Select which Moodle roles correspond to the Isard VDI environment role. If the selected role corresponds to both roles, the one that appears higher on the form will have priority';
$string['advanced'] = 'Role Advanced';
$string['advanceddesc'] = 'Select the Moodle roles that correspond to the Isard VDI advanced role';
$string['userrole'] = 'Role User';
$string['userroledesc'] = 'Select the Moodle roles that correspond to the Isard VDI user role';
$string['type'] = 'Type';
$string['type_help'] = 'Select the behavior type of the Isard VDI module.<br><ul><li><strong>Jump:</strong> Direct jump to ISARD VDI remote desktop</li></ul>';
$string['type_jump'] = 'Jump';
$string['exp'] = 'Expiration time';
$string['exp_desc'] = 'Token expiration time from when it is calculated until it can be used.';
$string['error_is_admin'] = 'This user is an administrator, so it cannot be redirected to the Isard VDI remote desktop.';
$string['error_is_not_enrol'] = 'This user is not enrol in this course.';
$string['error_not_role_valid'] = 'This user does not have a valid role. Check the plugin settings.';
$string['otherheading'] = 'Other configurations';
$string['logtoken'] = 'Token in Logs';
$string['logtokendesc'] = 'If this option is selected, the tokens will appear in the Moodle logs.';
$string['privacy:metadata:userid'] = 'User ID';
$string['privacy:metadata:courseid'] = "Id of the user's course where he is enrolled";
$string['privacy:metadata:roleisard'] = 'Role of the user in the ISARD VDI environment';
$string['privacy:metadata:rolemoodle'] = 'User role in the Moodle environment';
$string['privacy:metadata:username'] = 'User Name';
$string['privacy:metadata:email'] = 'User Email';
$string['privacy:metadata:fullname'] = 'User Fullname';
$string['privacy:metadata:firstname'] = 'User First Name';
$string['privacy:metadata:lastname'] = 'User Last Name';
$string['privacy:metadata:cmid'] = 'Course Module ID of ISARD VDI';
$string['privacy:metadata:cmname'] = 'Course Module Name of ISARD VDI';
$string['privacy:metadata:cmdescription'] = 'Course Module Description of ISARD VDI';
$string['privacy:metadata:courseshortname'] = 'Course Shortname';
$string['privacy:metadata:coursefullname'] = 'Course Fullname';
$string['privacy:metadata:externalpurpose'] = 'Sending user data to the external environment of ISARD VDI';
