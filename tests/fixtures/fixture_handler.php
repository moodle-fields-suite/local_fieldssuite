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

namespace local_fieldssuite\customfield;

/**
 * Area used by the tests: custom fields of course categories, with core capabilities.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class fixture_handler extends area_handler {
    /**
     * Capabilities of the area.
     *
     * @param string $type
     * @return string
     */
    public function get_capability(string $type): string {
        return [
            self::CAPABILITY_CONFIGURE => 'moodle/site:config',
            self::CAPABILITY_EDIT => 'moodle/category:manage',
            self::CAPABILITY_CHANGELOCKED => 'moodle/site:config',
        ][$type];
    }

    /**
     * Admin page of the area.
     *
     * @return string
     */
    public function get_admin_section(): string {
        return 'local_fieldssuite_fixture';
    }

    /**
     * Context of a category.
     *
     * @param int $instanceid
     * @return \context
     */
    public function get_instance_context(int $instanceid = 0): \context {
        return $instanceid ? \context_coursecat::instance($instanceid) : \context_system::instance();
    }

    /**
     * Category visible to the current user.
     *
     * @param int $instanceid
     * @return \stdClass|null
     */
    public function get_instance_info(int $instanceid): ?\stdClass {
        $category = \core_course_category::get($instanceid, IGNORE_MISSING);
        if (!$category || !$category->id) {
            return null;
        }
        return (object)[
            'name' => $category->get_formatted_name(),
            'url' => new \moodle_url('/course/index.php', ['categoryid' => $category->id]),
            'context' => $category->get_context(),
        ];
    }
}
