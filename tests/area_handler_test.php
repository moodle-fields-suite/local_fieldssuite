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

namespace local_fieldssuite;

use core_customfield\field_controller;
use local_fieldssuite\customfield\area_handler;
use local_fieldssuite\customfield\fixture_handler;
use local_fieldssuite\local\display;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once(__DIR__ . '/fixtures/fixture_handler.php');

/**
 * Tests for the base of the suite areas and the display of values.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_fieldssuite\customfield\area_handler
 * @covers     \local_fieldssuite\local\display
 */
final class area_handler_test extends \advanced_testcase {
    /** @var int|null Custom field category of the test area, created on demand. */
    private ?int $fieldcategoryid = null;

    /**
     * Creates a field of the test area.
     *
     * @param string $shortname
     * @param array $configdata
     * @return field_controller
     */
    private function create_field(string $shortname, array $configdata = []): field_controller {
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        if (!$this->fieldcategoryid) {
            $category = $generator->create_category(['component' => 'local_fieldssuite', 'area' => 'fixture']);
            $this->fieldcategoryid = (int)$category->get('id');
        }
        return $generator->create_field([
            'categoryid' => $this->fieldcategoryid,
            'shortname' => $shortname,
            'name' => ucfirst($shortname),
            'type' => 'text',
            'configdata' => $configdata,
        ]);
    }

    /**
     * Only handlers built on area_handler are accepted.
     */
    public function test_get_area_handler(): void {
        $this->assertInstanceOf(fixture_handler::class, area_handler::get_area_handler('local_fieldssuite', 'fixture'));
        $this->assertNull(area_handler::get_area_handler('core_course', 'course'));
        $this->assertNull(area_handler::get_area_handler('local_fieldssuite', 'missing'));
    }

    /**
     * Configuration and editing follow the capabilities of the area; locked fields need one more.
     */
    public function test_capabilities(): void {
        global $DB;
        $this->resetAfterTest();
        $category = $this->getDataGenerator()->create_category();
        $context = \context_coursecat::instance($category->id);
        $field = $this->create_field('department');
        $locked = $this->create_field('budget', ['locked' => 1]);
        $handler = fixture_handler::create();

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $this->assertFalse($handler->can_configure());
        $this->assertFalse($handler->can_edit($field, $category->id));

        $managerrole = $DB->get_field('role', 'id', ['shortname' => 'manager']);
        $this->getDataGenerator()->role_assign($managerrole, $user->id, $context->id);
        $this->assertTrue($handler->can_edit($field, $category->id));
        $this->assertFalse($handler->can_edit($locked, $category->id));

        $this->setAdminUser();
        $this->assertTrue($handler->can_configure());
        $this->assertTrue($handler->can_edit($locked, $category->id));
    }

    /**
     * The visibility setting decides who sees the value.
     */
    public function test_visibility(): void {
        $this->resetAfterTest();
        $category = $this->getDataGenerator()->create_category();
        $all = $this->create_field('public');
        $managers = $this->create_field('internal', ['visibility' => area_handler::VISIBLETOMANAGERS]);
        $nobody = $this->create_field('hidden', ['visibility' => area_handler::NOTVISIBLE]);
        $handler = fixture_handler::create();

        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertTrue($handler->can_view($all, $category->id));
        $this->assertFalse($handler->can_view($managers, $category->id));
        $this->assertFalse($handler->can_view($nobody, $category->id));

        $this->setAdminUser();
        $this->assertTrue($handler->can_view($managers, $category->id));
        $this->assertFalse($handler->can_view($nobody, $category->id));
    }

    /**
     * The common pages are addressed by component and area.
     */
    public function test_urls(): void {
        $handler = fixture_handler::create();
        $this->assertSame('/local/fieldssuite/manage.php', strtok($handler->get_configuration_url()->out_as_local_url(false), '?'));
        $this->assertSame(['component' => 'local_fieldssuite', 'area' => 'fixture'], $handler->get_configuration_url()->params());
        $this->assertSame(
            ['component' => 'local_fieldssuite', 'area' => 'fixture', 'id' => '7'],
            $handler->get_edit_url(7)->params()
        );
    }

    /**
     * Values are displayed only when visible and not empty, and escaped.
     */
    public function test_render_values(): void {
        $this->resetAfterTest();
        $category = $this->getDataGenerator()->create_category();
        $public = $this->create_field('public');
        $internal = $this->create_field('internal', ['visibility' => area_handler::VISIBLETOMANAGERS]);
        $this->create_field('empty');
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $generator->add_instance_data($public, $category->id, 'R&D "Engineering"');
        $generator->add_instance_data($internal, $category->id, 'Secret');
        $handler = fixture_handler::create();

        $this->setUser($this->getDataGenerator()->create_user());
        $html = display::render_values($handler, $category->id);
        $this->assertStringContainsString('R&amp;D', $html);
        $this->assertStringContainsString('data-area="fixture"', $html);
        $this->assertStringNotContainsString('Secret', $html);
        $this->assertStringNotContainsString('Empty', $html);

        $other = $this->getDataGenerator()->create_category();
        $this->assertSame('', display::render_values($handler, $other->id));
    }
}
