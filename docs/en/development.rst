Development
===========

Building an area plugin
-----------------------

An area plugin (usually ``tool_<entity>customfields``) needs:

1. ``version.php`` with ``$plugin->dependencies = ['local_fieldssuite' => 2026103100];``.
2. A handler named ``<component>\customfield\<area>_handler`` (core parses this name) that extends ``local_fieldssuite\customfield\area_handler`` and implements:

   * ``get_capability($type)``: the capability for ``CAPABILITY_CONFIGURE``, ``CAPABILITY_EDIT`` and ``CAPABILITY_CHANGELOCKED``;
   * ``get_admin_section()``: the name of the admin external page that manages the fields;
   * ``get_instance_context($instanceid)``: the context of an item;
   * ``get_instance_info($instanceid)``: ``name``, ``url`` and ``context`` of an item the user can see, or ``null``;
   * optionally ``set_up_edit_page()``, to give the edit page the navigation of the entity.

3. ``db/access.php`` with the three capabilities, and their strings.
4. ``settings.php`` with an ``admin_externalpage`` that points to ``/local/fieldssuite/manage.php?component=<component>&area=<area>`` with the configure capability.
5. A navigation link to ``$handler->get_edit_url($instanceid)`` where the entity shows its settings.
6. An event observer that calls ``$handler->delete_instance($instanceid)`` when the entity is deleted.
7. Optionally, an output hook that calls ``local_fieldssuite\local\display::add_to_page()`` on the page of the entity.

``tool_categorycustomfields`` is the reference implementation.

Web service
-----------

``local_fieldssuite_get_values`` takes ``component``, ``area`` and a list of ``instanceids`` and returns, for each item the user can see, the fields the user can see (``shortname``, ``name``, ``type``, ``value`` and ``displayvalue``). Items that do not exist or that the user cannot see are reported in ``warnings``. Area plugins can keep a web service of their own and reuse ``local_fieldssuite\external\get_values::get_instances_values()``.
