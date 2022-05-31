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

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.

function theme_boostioc_get_main_scss_content($theme)
{

    global $CFG;

    $scss = file_get_contents($CFG->dirroot . '/theme/boostioc/scss/pre.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/fontawesome.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/bootstrap.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/moodle.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boostioc/scss/post.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boostioc/scss/components.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boostioc/scss/plantilles-ges.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boostioc/scss/plantilles-components-eoi.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/boostioc/scss/plantilles-components-fp.scss');

    return $scss;
}
