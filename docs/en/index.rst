moodle-local_fieldssuite
========================

**Common code of the custom field areas of the Moodle Fields Suite.**

The `Moodle Fields Suite <https://moodle-fields-suite.github.io>`_ adds custom fields where Moodle has none, such as course categories. Each entity gets its own small area plugin; this plugin holds what they share, so every area behaves the same way and a fix reaches all of them.

* **Area settings:** every field of an area is *Locked* or not and *Visible to* everyone, to the users who can edit the values or to nobody.
* **Common pages:** one page manages the fields of an area and one page edits the values of an item, for every area.
* **Display:** area plugins show the visible values on core pages, next to the element they choose.
* **Web service:** ``local_fieldssuite_get_values`` returns the values of the items the user can see.

It does nothing on its own: install it together with an area plugin, such as `tool_categorycustomfields <https://moodle-fields-suite.github.io/tool_categorycustomfields/>`_.

Versão em português: `Português (Brasil) <../pt-br/index.html>`_.

.. toctree::
   :maxdepth: 2
   :caption: Contents

   installation
   development

Links
-----

* Source code and issues: https://github.com/moodle-fields-suite/local_fieldssuite
