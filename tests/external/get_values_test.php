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
use local_fieldssuite\customfield\area_handler;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once(__DIR__ . '/../fixtures/fixture_handler.php');

/**
 * Tests for the get_values external function.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_fieldssuite\external\get_values
 */
final class get_values_test extends \core_external\tests\externallib_testcase {
    /**
     * Visible values of visible instances are returned; others give a warning.
     */
    public function test_execute(): void {
        $this->resetAfterTest();
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $cfcategory = $generator->create_category(['component' => 'local_fieldssuite', 'area' => 'fixture']);
        $public = $generator->create_field(['categoryid' => $cfcategory->get('id'), 'shortname' => 'department',
            'name' => 'Department']);
        $internal = $generator->create_field(['categoryid' => $cfcategory->get('id'), 'shortname' => 'budget',
            'configdata' => ['visibility' => area_handler::VISIBLETOMANAGERS]]);

        $category = $this->getDataGenerator()->create_category();
        $hidden = $this->getDataGenerator()->create_category(['visible' => 0]);
        $generator->add_instance_data($public, $category->id, 'Engineering');
        $generator->add_instance_data($internal, $category->id, 'ENG-2026');
        $generator->add_instance_data($public, $hidden->id, 'Hidden');

        $this->setUser($this->getDataGenerator()->create_user());
        $result = get_values::execute('local_fieldssuite', 'fixture', [$category->id, $hidden->id, 999999]);
        $result = external_api::clean_returnvalue(get_values::execute_returns(), $result);

        $this->assertCount(1, $result['instances']);
        $this->assertEquals($category->id, $result['instances'][0]['id']);
        $fields = $result['instances'][0]['fields'];
        $this->assertCount(1, $fields);
        $this->assertSame('department', $fields[0]['shortname']);
        $this->assertSame('Engineering', $fields[0]['value']);
        $this->assertCount(2, $result['warnings']);

        $this->setAdminUser();
        $result = get_values::execute('local_fieldssuite', 'fixture', [$category->id, $hidden->id]);
        $result = external_api::clean_returnvalue(get_values::execute_returns(), $result);
        $this->assertCount(2, $result['instances']);
        $this->assertCount(2, $result['instances'][0]['fields']);
    }

    /**
     * Areas not built on area_handler are refused.
     */
    public function test_unknown_area(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        $this->expectException(\moodle_exception::class);
        get_values::execute('core_course', 'course', [1]);
    }
}
