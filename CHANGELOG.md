# Changelog

## 1.1.0 - 2026-11-01

### Added
- `display::render_values()` and `display::add_to_page()` can show an *Edit custom fields* link to users who can edit the values, even when there are no values yet, for entities without a settings menu (competencies, frameworks, badges).

## 1.0.0 - 2026-10-31

First release, extracted from `tool_categorycustomfields` 1.0.0.

### Added
- `local_fieldssuite\customfield\area_handler`: base class of the custom field areas of the suite, with the *Locked* and *Visible to* area settings and the permission checks; area plugins give the capabilities, the instance context, the entity name and URL and the admin page.
- Common pages: `manage.php` (fields of an area, behind the area's admin page) and `edit.php` (values of an instance).
- `local_fieldssuite\local\display`: values of an instance on a core page, from an output hook, moved next to an element by the `local_fieldssuite/placement` module.
- `local_fieldssuite_get_values` web service (also available to AJAX and the mobile app), returning the values the user can see; area plugins can reuse its logic.
- Privacy API provider, PHPUnit tests, CI with moodle-plugin-ci (Moodle 4.5 and 5.3, PostgreSQL and MariaDB), documentation in English and Brazilian Portuguese, release by draft and verification.
