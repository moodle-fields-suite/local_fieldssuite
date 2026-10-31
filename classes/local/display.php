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

namespace local_fieldssuite\local;

use local_fieldssuite\customfield\area_handler;

/**
 * Shows the custom field values of an instance on a core page.
 *
 * Area plugins call it from an output hook (for example after_standard_main_region_html_generation).
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class display {
    /**
     * HTML with the non-empty values of an instance that the current user can see.
     *
     * @param area_handler $handler
     * @param int $instanceid
     * @return string Empty when there is nothing to show.
     */
    public static function render_values(area_handler $handler, int $instanceid): string {
        global $OUTPUT;
        $fields = [];
        foreach ($handler->get_instance_data($instanceid) as $data) {
            $value = $data->export_value();
            if ($value === null || $value === '') {
                continue;
            }
            $fields[] = [
                'name' => $data->get_field()->get_formatted_name(),
                'shortname' => $data->get_field()->get('shortname'),
                'type' => $data->get_field()->get('type'),
                'value' => $value,
            ];
        }
        if (!$fields) {
            return '';
        }
        return $OUTPUT->render_from_template('local_fieldssuite/values', [
            'component' => $handler->get_component(),
            'area' => $handler->get_area(),
            'fields' => $fields,
        ]);
    }

    /**
     * Adds the values to the output of a hook and moves them next to an element of the page.
     *
     * @param \core\hook\output\after_standard_main_region_html_generation $hook
     * @param area_handler $handler
     * @param int $instanceid
     * @param string $anchor CSS selector, inside the main region, of the element the values follow; if it is
     *     missing, the values go to the top of the main region.
     */
    public static function add_to_page(
        \core\hook\output\after_standard_main_region_html_generation $hook,
        area_handler $handler,
        int $instanceid,
        string $anchor = ''
    ): void {
        global $PAGE;
        $html = self::render_values($handler, $instanceid);
        if ($html === '') {
            return;
        }
        $hook->add_html($html);
        $PAGE->requires->js_call_amd('local_fieldssuite/placement', 'init', [$anchor]);
    }
}
