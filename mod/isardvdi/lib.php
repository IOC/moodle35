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
 * Library of interface functions and constants.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return int True if the feature is supported, null otherwise.
 */
function isardvdi_supports(string $feature) {
    switch($feature) {
        case FEATURE_COMMENT:
        case FEATURE_COMPLETION_TRACKS_VIEWS:
        case FEATURE_BACKUP_MOODLE2:
        case FEATURE_SHOW_DESCRIPTION:
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_PLAGIARISM:
        case FEATURE_GROUPS:
        case FEATURE_GROUPINGS:
        case FEATURE_COMPLETION_HAS_RULES:
        case FEATURE_USES_QUESTIONS:
        case FEATURE_GRADE_HAS_GRADE:
        case FEATURE_GRADE_OUTCOMES:
        case FEATURE_CONTROLS_GRADE_VISIBILITY:
        case FEATURE_ADVANCED_GRADING:
            return false;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_isardvdi into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_isardvdi_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 * @throws dml_exception
 */
function isardvdi_add_instance(object $moduleinstance, $mform = null): int {
    global $DB;
    $moduleinstance->timecreated = time();
    $moduleinstance->id = $DB->insert_record('isardvdi', $moduleinstance);
    return $moduleinstance->id;

}

/**
 * Updates an instance of the mod_isardvdi in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_isardvdi_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 * @throws dml_exception
 */
function isardvdi_update_instance(object $moduleinstance, $mform = null): bool {
    global $DB;
    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;
    $DB->update_record('isardvdi', $moduleinstance);
    return true;
}

/**
 * Removes an instance of the mod_isardvdi from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 * @throws dml_exception
 */
function isardvdi_delete_instance(int $id): bool {
    global $DB;
    $exists = $DB->get_record('isardvdi', array( 'id' => $id ));
    if (!$exists) {
        return false;
    }
    $DB->delete_records('isardvdi', array( 'id' => $id ));
    return true;
}

/**
 * Running addtional permission check on plugin, for example, plugins
 * may have switch to turn on/off comments option, this callback will
 * affect UI display, not like pluginname_comment_validate only throw
 * exceptions.
 * Capability check has been done in comment->check_permissions(), we
 * don't need to do it again here.
 *
 * @package  mod_isardvdi
 * @category comment
 *
 * @param stdClass $commentparam {
 *              context     => context the context object
 *              courseid    => int course id
 *              cm          => stdClass course module object
 *              commentarea => string comment area
 *              itemid      => int itemid
 * }
 * @return array
 */
function isardvdi_comment_permissions(stdClass $commentparam): array {
    return array( 'post' => true, 'view' => true );
}

/**
 * Validate comment parameter before perform other comments actions
 *
 * @package  mod_isardvdi
 * @category comment
 *
 * @param stdClass $commentparam {
 *              context     => context the context object
 *              courseid    => int course id
 *              cm          => stdClass course module object
 *              commentarea => string comment area
 *              itemid      => int itemid
 * }
 * @return boolean
 */
function isardvdi_comment_validate(stdClass $commentparam): bool {
    return true;
}

