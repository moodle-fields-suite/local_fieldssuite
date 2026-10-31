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

namespace local_fieldssuite\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;
use core_external\external_warnings;
use local_fieldssuite\customfield\area_handler;

/**
 * Returns the custom field values of instances of a suite area.
 *
 * Only instances the user can see are returned, and only the fields the user can see in each of them.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_values extends external_api {
    /**
     * Parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'component' => new external_value(PARAM_COMPONENT, 'Component of the area, for example tool_categorycustomfields'),
            'area' => new external_value(PARAM_ALPHANUMEXT, 'Area, for example category'),
            'instanceids' => new external_multiple_structure(
                new external_value(PARAM_INT, 'Instance id, for example a course category id'),
                'Instance ids'
            ),
        ]);
    }

    /**
     * Returns the values.
     *
     * @param string $component
     * @param string $area
     * @param int[] $instanceids
     * @return array
     */
    public static function execute(string $component, string $area, array $instanceids): array {
        $params = self::validate_parameters(
            self::execute_parameters(),
            ['component' => $component, 'area' => $area, 'instanceids' => $instanceids]
        );
        self::validate_context(\context_system::instance());

        $handler = area_handler::get_area_handler($params['component'], $params['area']);
        if (!$handler) {
            throw new \moodle_exception('unknownarea', 'local_fieldssuite');
        }
        return self::get_instances_values($handler, $params['instanceids']);
    }

    /**
     * Values of the instances the user can see, with a warning for the others.
     *
     * @param area_handler $handler
     * @param int[] $instanceids
     * @return array ['instances' => [...], 'warnings' => [...]]
     */
    public static function get_instances_values(area_handler $handler, array $instanceids): array {
        $instances = [];
        $warnings = [];
        foreach (array_unique($instanceids) as $instanceid) {
            $info = $handler->get_instance_info((int)$instanceid);
            if (!$info) {
                $warnings[] = [
                    'item' => $handler->get_area(),
                    'itemid' => $instanceid,
                    'warningcode' => 'notfound',
                    'message' => 'Instance not found or not visible.',
                ];
                continue;
            }
            self::validate_context($info->context);

            $fields = [];
            foreach ($handler->get_instance_data((int)$instanceid) as $data) {
                $field = $data->get_field();
                $fields[] = [
                    'shortname' => $field->get('shortname'),
                    'name' => $field->get_formatted_name(),
                    'type' => $field->get('type'),
                    'value' => (string)$data->get_value(),
                    'displayvalue' => (string)$data->export_value(),
                ];
            }
            $instances[] = ['id' => (int)$instanceid, 'fields' => $fields];
        }
        return ['instances' => $instances, 'warnings' => $warnings];
    }

    /**
     * Structure of the fields of an instance.
     *
     * @return external_multiple_structure
     */
    public static function fields_structure(): external_multiple_structure {
        return new external_multiple_structure(new external_single_structure([
            'shortname' => new external_value(PARAM_ALPHANUMEXT, 'Field short name'),
            'name' => new external_value(PARAM_TEXT, 'Field name'),
            'type' => new external_value(PARAM_COMPONENT, 'Field type'),
            'value' => new external_value(PARAM_RAW, 'Stored value'),
            'displayvalue' => new external_value(PARAM_RAW, 'Value formatted for display'),
        ]));
    }

    /**
     * Returned structure.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'instances' => new external_multiple_structure(new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Instance id'),
                'fields' => self::fields_structure(),
            ])),
            'warnings' => new external_warnings(),
        ]);
    }
}
