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

use core_customfield\field_controller;

/**
 * Base of the custom field areas of the Moodle Fields Suite.
 *
 * An area plugin declares a handler named `<component>\customfield\<area>_handler` (the name core parses) that
 * extends this class and tells which entity it extends: its context, its capabilities, its name and URL, and
 * where its values are managed. This class gives the rest: the area settings (locked and visibility), the
 * permission checks, the common edit page and the read web service.
 *
 * @package    local_fieldssuite
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class area_handler extends \core_customfield\handler {
    /** @var int Visible to everyone who can see the entity. */
    const VISIBLETOALL = 2;

    /** @var int Visible to those who can edit the values of the entity. */
    const VISIBLETOMANAGERS = 1;

    /** @var int Not shown with the entity nor returned by the web service. */
    const NOTVISIBLE = 0;

    /** @var string Capability that configures the fields. */
    const CAPABILITY_CONFIGURE = 'configure';

    /** @var string Capability that edits the values of an entity. */
    const CAPABILITY_EDIT = 'edit';

    /** @var string Capability that edits locked fields. */
    const CAPABILITY_CHANGELOCKED = 'changelocked';

    /**
     * Name of one of the capabilities of the area.
     *
     * @param string $type One of the CAPABILITY_* constants.
     * @return string Capability name, for example 'tool/categorycustomfields:edit'.
     */
    abstract public function get_capability(string $type): string;

    /**
     * Name of the admin external page that manages the fields of the area.
     *
     * @return string
     */
    abstract public function get_admin_section(): string;

    /**
     * The entity of an instance, when it exists and the current user can see it.
     *
     * @param int $instanceid
     * @return \stdClass|null Object with `name` (formatted), `url` (\moodle_url) and `context`, or null.
     */
    abstract public function get_instance_info(int $instanceid): ?\stdClass;

    /**
     * Sets up the page that edits the values of an instance (context, layout, navigation).
     *
     * @param int $instanceid
     * @param \moodle_url $url URL of the edit page.
     */
    public function set_up_edit_page(int $instanceid, \moodle_url $url): void {
        global $PAGE;
        $PAGE->set_context($this->get_instance_context($instanceid));
        $PAGE->set_url($url);
        $PAGE->set_pagelayout('admin');
        $PAGE->add_body_class('limitedwidth');
    }

    /**
     * The current user can manage the fields of the area.
     *
     * @return bool
     */
    public function can_configure(): bool {
        return has_capability($this->get_capability(self::CAPABILITY_CONFIGURE), $this->get_configuration_context());
    }

    /**
     * The current user can edit the value of a field in an instance.
     *
     * @param field_controller $field
     * @param int $instanceid
     * @return bool
     */
    public function can_edit(field_controller $field, int $instanceid = 0): bool {
        $context = $this->get_instance_context($instanceid);
        if (!has_capability($this->get_capability(self::CAPABILITY_EDIT), $context)) {
            return false;
        }
        return !$field->get_configdata_property('locked') ||
            has_capability($this->get_capability(self::CAPABILITY_CHANGELOCKED), $context);
    }

    /**
     * The current user can see the value of a field in an instance.
     *
     * @param field_controller $field
     * @param int $instanceid
     * @return bool
     */
    public function can_view(field_controller $field, int $instanceid): bool {
        $visibility = $field->get_configdata_property('visibility') ?? self::VISIBLETOALL;
        if ($visibility == self::NOTVISIBLE) {
            return false;
        } else if ($visibility == self::VISIBLETOMANAGERS) {
            return has_capability($this->get_capability(self::CAPABILITY_EDIT), $this->get_instance_context($instanceid));
        }
        return true;
    }

    /**
     * The fields are configured for the whole site.
     *
     * @return \context
     */
    public function get_configuration_context(): \context {
        return \context_system::instance();
    }

    /**
     * URL of the page that manages the fields of the area.
     *
     * @return \moodle_url
     */
    public function get_configuration_url(): \moodle_url {
        return new \moodle_url(
            '/local/fieldssuite/manage.php',
            ['component' => $this->get_component(), 'area' => $this->get_area()]
        );
    }

    /**
     * URL of the common page that edits the values of an instance.
     *
     * @param int $instanceid
     * @return \moodle_url
     */
    public function get_edit_url(int $instanceid): \moodle_url {
        return new \moodle_url(
            '/local/fieldssuite/edit.php',
            ['component' => $this->get_component(), 'area' => $this->get_area(), 'id' => $instanceid]
        );
    }

    /**
     * Area settings added to the field configuration form.
     *
     * @param \MoodleQuickForm $mform
     */
    public function config_form_definition(\MoodleQuickForm $mform) {
        $mform->addElement('header', 'fieldssuite_handler_header', get_string('areasettings', 'local_fieldssuite'));
        $mform->setExpanded('fieldssuite_handler_header', true);

        $mform->addElement('selectyesno', 'configdata[locked]', get_string('locked', 'local_fieldssuite'));
        $mform->addHelpButton('configdata[locked]', 'locked', 'local_fieldssuite');

        $options = [
            self::VISIBLETOALL => get_string('visibletoall', 'local_fieldssuite'),
            self::VISIBLETOMANAGERS => get_string('visibletomanagers', 'local_fieldssuite'),
            self::NOTVISIBLE => get_string('notvisible', 'local_fieldssuite'),
        ];
        $mform->addElement('select', 'configdata[visibility]', get_string('visibility', 'local_fieldssuite'), $options);
        $mform->addHelpButton('configdata[visibility]', 'visibility', 'local_fieldssuite');
    }

    /**
     * Handler of a suite area, or null when the component and area do not name one.
     *
     * Only areas built on this class are accepted, so the common pages never serve other custom field areas.
     *
     * @param string $component
     * @param string $area
     * @return self|null
     */
    public static function get_area_handler(string $component, string $area): ?self {
        $classname = "\\{$component}\\customfield\\{$area}_handler";
        if (!class_exists($classname) || !is_subclass_of($classname, self::class)) {
            return null;
        }
        return $classname::create();
    }
}
