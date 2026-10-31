moodle-local_fieldssuite
========================

**Código comum das áreas de campos personalizados da Moodle Fields Suite.**

A `Moodle Fields Suite <https://moodle-fields-suite.github.io>`_ acrescenta campos personalizados onde o Moodle não tem, como nas categorias de curso. Cada entidade ganha um pequeno plugin de área; este plugin guarda o que eles compartilham, para que todas as áreas se comportem igual e uma correção chegue a todas.

* **Configurações da área:** cada campo de uma área é *Bloqueado* ou não e *Visível* para todos, para quem pode editar os valores ou para ninguém.
* **Páginas comuns:** uma página gerencia os campos de uma área e outra edita os valores de um item, para todas as áreas.
* **Exibição:** os plugins de área mostram os valores visíveis em páginas do Moodle, junto ao elemento que escolherem.
* **Web service:** ``local_fieldssuite_get_values`` devolve os valores dos itens que o usuário pode ver.

Sozinho ele não faz nada: instale-o junto com um plugin de área, como o `tool_categorycustomfields <https://moodle-fields-suite.github.io/tool_categorycustomfields/>`_.

English version: `English <../en/index.html>`_.

.. toctree::
   :maxdepth: 2
   :caption: Conteúdo

   installation
   development

Links
-----

* Código-fonte e issues: https://github.com/moodle-fields-suite/local_fieldssuite
