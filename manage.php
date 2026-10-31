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

/**
 * Manages the custom fields of a suite area.
 *
 * Each area plugin registers an admin external page that points here; its capability protects the page.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_fieldssuite\customfield\area_handler;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$component = required_param('component', PARAM_COMPONENT);
$area = required_param('area', PARAM_ALPHANUMEXT);
$handler = area_handler::get_area_handler($component, $area);

$section = $handler ? $handler->get_admin_section() : '';
admin_externalpage_setup($section);

$PAGE->add_body_class('limitedwidth');
$output = $PAGE->get_renderer('core_customfield');
$outputpage = new \core_customfield\output\management($handler);

echo $output->header(),
    $output->heading(admin_get_root()->locate($section)->visiblename),
    $output->render($outputpage),
    $output->footer();
