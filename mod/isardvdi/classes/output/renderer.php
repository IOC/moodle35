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
 * Class renderer
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_isardvdi\output;

use moodle_exception;
use plugin_renderer_base;

/**
 * Class renderer
 *
 * @package     mod_isardvdi
 * @copyright   2022 Tresipunt - Antonio Manzano <contacte@tresipunt.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Defer to template.
     *
     * @param message_view $page
     * @return string html for the page
     * @throws moodle_exception
     */
    public function render_message_view(message_view $page): string {
        $data = $page->export_for_template($this);
        return parent::render_from_template('mod_isardvdi/message_view', $data);
    }

    /**
     * Defer to template.
     *
     * @param index_view $page
     * @return string html for the page
     * @throws moodle_exception
     */
    public function render_index_view(index_view $page): string {
        $data = $page->export_for_template($this);
        return parent::render_from_template('mod_isardvdi/index_view', $data);
    }

}
