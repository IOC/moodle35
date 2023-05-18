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
 * mod_isardvdi data generator.
 *
 * @package    mod_isardvdi
 * @category   test
 * @copyright  2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * mod_isardvdi data generator class.
 *
 * @package    mod_isardvdi
 * @category   test
 * @copyright  2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_isardvdi_generator extends testing_module_generator {

    /**
     * Create Instance.
     *
     * @param null $record
     * @param array|null $options
     * @return stdClass
     * @throws coding_exception
     */
    public function create_instance($record = null, array $options = null): stdClass {
        global $USER;
        $record = (object)(array)$record;

        if (!isset($record->name)) {
            $record->name = 'Test IsardVDI';
        }

        if (!isset($record->intro)) {
            $record->intro = 'Description IsardVDI';
        }

        if (!isset($record->introformat)) {
            $record->introformat = 1;
        }

        if (!isset($record->type)) {
            $record->type = 0;
        }

        if (!isset($record->userid)) {
            $record->userid = $USER->id;
        }

        return parent::create_instance($record, (array)$options);
    }
}
