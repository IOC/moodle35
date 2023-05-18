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
 * Backup Activity Structure Step.
 *
 * @package     mod_isardvdi
 * @category    backup
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_isardvdi_activity_task
 */

/**
 * Define the complete isardvdi structure for backup, with file and id annotations
 */
class backup_isardvdi_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define Structure.
     *
     * @return backup_nested_element
     * @throws base_element_struct_exception
     * @throws base_step_exception
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $isardvdi = new backup_nested_element('isardvdi', array('id'), array(
            'name', 'userid', 'type', 'intro', 'introformat', 'timemodified', 'timecreated'));

        // Build the tree.
        // (love this).

        // Define sources.
        $isardvdi->set_source_table('isardvdi', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.
        // (none).

        // Define file annotations.
        $isardvdi->annotate_files('mod_isardvdi', 'intro', null);
        // This file area hasn't itemid.

        // Return the root element (isardvdi), wrapped into standard activity structure.
        return $this->prepare_activity_structure($isardvdi);
    }
}
