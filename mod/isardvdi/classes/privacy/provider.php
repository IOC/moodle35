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
 * Privacy Subsystem implementation for mod_isardvdi.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_isardvdi\privacy;

use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

/**
 * The mod_isardvdi module does not store any data.
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        // This plugin does not store any data itself.
        // It has no database tables, and it purely acts as a conduit, sending data externally.
        \core_privacy\local\metadata\provider,
        \core_privacy\local\request\core_userlist_provider,
        \core_privacy\local\request\plugin\provider {

    /**
     * Return the fields which contain personal data.
     *
     * @param collection $collection a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_external_location_link(
                'isardvdi',
                [
                        'user_id' => 'privacy:metadata:userid',
                        'group_id' => 'privacy:metadata:courseid',
                        'role_isard' => 'privacy:metadata:roleisard',
                        'role_moodle' => 'privacy:metadata:rolemoodle',
                        'username' => 'privacy:metadata:username',
                        'email' => 'privacy:metadata:email',
                        'firstname' => 'privacy:metadata:firstname',
                        'lastname' => 'privacy:metadata:lastname',
                        'name' => 'privacy:metadata:fullname',
                        'cmid' => 'privacy:metadata:cmid',
                        'cmname' => 'privacy:metadata:cmname',
                        'cmdescription' => 'privacy:metadata:cmdescription',
                        'course_id' => 'privacy:metadata:courseid',
                        'courseshortname' => 'privacy:metadata:courseshortname',
                        'coursefullname' => 'privacy:metadata:coursefullname',
                ],
                'privacy:metadata:externalpurpose'
        );
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid the userid.
     * @return contextlist the list of contexts containing user info for the user.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        return new contextlist();
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context the context to delete in.
     */
    public static function delete_data_for_all_users_in_context(context $context) {
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist       $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
    }

}
