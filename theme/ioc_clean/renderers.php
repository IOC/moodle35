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

defined('MOODLE_INTERNAL') || die();

class theme_ioc_clean_core_renderer extends theme_bootstrapbase_core_renderer {

    public function standard_head_html() {
        $output = parent::standard_head_html();

        $this->page->requires->js('/theme/ioc_clean/javascript/clipboard.min.js', true);

        return $output;
    }

    public function standard_end_of_body_html() {
        $output = parent::standard_end_of_body_html();

        $jsmodule = array(
            'name' => 'theme_ioc_clean',
            'fullpath' => '/theme/ioc_clean/javascript/ioc.js',
        );
        $this->page->requires->js_init_call('M.theme_ioc_clean.init', array(), true, $jsmodule);

        return $output;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'clearfix'));
        $html .= html_writer::tag('span', $this->context_header(), array('class' => 'visible-phone'));
        $html .= html_writer::start_div('clearfix', array('id' => 'page-navbar'));
        $html .= html_writer::tag('nav', $this->navbar(), array('class' => 'breadcrumb-nav'));
        $html .= html_writer::div($this->page_heading_button(), 'breadcrumb-button');
        $html .= html_writer::end_div();
        $html .= html_writer::tag('div', $this->course_header(), array('id' => 'course-header'));
        $html .= html_writer::end_tag('header');
        return $html;
    }
}
