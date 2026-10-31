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

namespace local_fieldssuite\privacy;

use core_privacy\tests\provider_testcase;

/**
 * Tests for the privacy provider of local_fieldssuite.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_fieldssuite\privacy\provider
 */
final class provider_test extends provider_testcase {
    /**
     * The plugin declares that it stores no personal data.
     */
    public function test_get_reason(): void {
        $this->assertSame('privacy:metadata', provider::get_reason());
        $this->assertNotEmpty(get_string(provider::get_reason(), 'local_fieldssuite'));
    }
}
