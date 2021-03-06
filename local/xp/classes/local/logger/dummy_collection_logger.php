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
 * Dummy collection logger.
 *
 * @package    local_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_xp\local\logger;
defined('MOODLE_INTERNAL') || die();

use DateTime;
use block_xp\local\activity\user_recent_activity_repository;
use block_xp\local\logger\collection_logger_with_group_reset;
use block_xp\local\logger\reason_collection_logger;
use block_xp\local\reason\reason;

/**
 * Dummy collection logger.
 *
 * @package    local_xp
 * @copyright  2017 Frédéric Massart
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class dummy_collection_logger implements
        reason_collection_logger,
        collection_logger_with_group_reset,
        collection_counts_indicator,
        reason_occurance_indicator,
        user_recent_activity_repository
    {

    public function count_collections_since($userid, DateTime $since) {
        return 0;
    }

    public function delete_older_than(DateTime $dt) {
    }

    public function get_collected_points_since($userid, DateTime $since) {
        return 0;
    }

    public function get_user_recent_activity($userid, $count = 0) {
        return [];
    }

    public function has_reason_happened_since($userid, reason $reason, DateTime $since) {
        return false;
    }

    public function log($userid, $points, $signature, DateTime $time = null) {
    }

    public function log_reason($id, $points, reason $reason, DateTime $time = null) {
    }

    public function reset() {
    }

    public function reset_by_group($groupid) {
    }

}
