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

namespace local_fieldssuite\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Form that edits the custom field values of an instance of a suite area.
 *
 * Custom data: 'handler' (area_handler) and 'instanceid' (int).
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_form extends \moodleform {
    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;
        $handler = $this->_customdata['handler'];
        $instanceid = $this->_customdata['instanceid'];

        $mform->addElement('hidden', 'id', $instanceid);
        $mform->setType('id', PARAM_INT);

        $handler->instance_form_definition($mform, $instanceid);

        $this->add_action_buttons();
    }

    /**
     * Lets the fields adjust themselves after the data is set.
     */
    public function definition_after_data() {
        $this->_customdata['handler']->instance_form_definition_after_data($this->_form, $this->_customdata['instanceid']);
    }

    /**
     * Validates the values with the field types.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return array_merge($errors, $this->_customdata['handler']->instance_form_validation($data, $files));
    }
}
