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
 * Edits the custom field values of an instance of a suite area.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_fieldssuite\customfield\area_handler;

require_once(__DIR__ . '/../../config.php');

$component = required_param('component', PARAM_COMPONENT);
$area = required_param('area', PARAM_ALPHANUMEXT);
$id = required_param('id', PARAM_INT);

require_login();

$handler = area_handler::get_area_handler($component, $area);
if (!$handler) {
    throw new moodle_exception('unknownarea', 'local_fieldssuite');
}
$info = $handler->get_instance_info($id);
if (!$info) {
    throw new moodle_exception('instancenotfound', 'local_fieldssuite');
}
require_capability($handler->get_capability(area_handler::CAPABILITY_EDIT), $info->context);

$url = $handler->get_edit_url($id);
$handler->set_up_edit_page($id, $url);
$PAGE->set_title(get_string('editvaluesof', 'local_fieldssuite', $info->name));
$PAGE->set_heading($info->name);
$PAGE->navbar->add(get_string('editvalues', 'local_fieldssuite'), $url);

$form = new \local_fieldssuite\form\edit_form($url, ['handler' => $handler, 'instanceid' => $id]);
$instance = (object)['id' => $id];
$handler->instance_form_before_set_data($instance);
$form->set_data($instance);

if ($form->is_cancelled()) {
    redirect($info->url);
} else if ($data = $form->get_data()) {
    $handler->instance_form_save($data);
    redirect(
        $info->url,
        get_string('valuessaved', 'local_fieldssuite'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editvalues', 'local_fieldssuite'));
if (!$handler->get_editable_fields($id)) {
    if ($handler->can_configure()) {
        $link = html_writer::link($handler->get_configuration_url(), get_string('managefields', 'local_fieldssuite'));
        echo $OUTPUT->notification(get_string('nofieldsmanage', 'local_fieldssuite', $link), 'info');
    } else {
        echo $OUTPUT->notification(get_string('nofields', 'local_fieldssuite'), 'info');
    }
} else {
    $form->display();
}
echo $OUTPUT->footer();
