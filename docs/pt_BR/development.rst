Desenvolvimento
===============

Criando um plugin de área
-------------------------

Um plugin de área (normalmente ``tool_<entidade>customfields``) precisa de:

1. ``version.php`` com ``$plugin->dependencies = ['local_fieldssuite' => 2026103100];``.
2. Uma classe ``<componente>\customfield\<área>_handler`` (o Moodle lê esse nome) que estende ``local_fieldssuite\customfield\area_handler`` e implementa:

   * ``get_capability($tipo)``: a permissão de ``CAPABILITY_CONFIGURE``, ``CAPABILITY_EDIT`` e ``CAPABILITY_CHANGELOCKED``;
   * ``get_admin_section()``: o nome da página externa de administração que gerencia os campos;
   * ``get_instance_context($instanceid)``: o contexto de um item;
   * ``get_instance_info($instanceid)``: ``name``, ``url`` e ``context`` de um item que o usuário pode ver, ou ``null``;
   * opcionalmente ``set_up_edit_page()``, para dar à página de edição a navegação da entidade.

3. ``db/access.php`` com as três permissões e as suas strings.
4. ``settings.php`` com uma ``admin_externalpage`` que aponta para ``/local/fieldssuite/manage.php?component=<componente>&area=<área>`` com a permissão de configurar.
5. Um link de navegação para ``$handler->get_edit_url($instanceid)`` onde a entidade mostra as suas configurações.
6. Um observador de evento que chama ``$handler->delete_instance($instanceid)`` quando a entidade é excluída.
7. Opcionalmente, um hook de saída que chama ``local_fieldssuite\local\display::add_to_page()`` na página da entidade. Quando a entidade não tem menu de configurações para o link do passo 5, passe ``$editlink = true``: a caixa passa a mostrar também um link *Edit custom fields* para quem pode editar os valores.

O ``tool_categorycustomfields`` é a implementação de referência.

Web service
-----------

``local_fieldssuite_get_values`` recebe ``component``, ``area`` e uma lista de ``instanceids`` e devolve, para cada item que o usuário pode ver, os campos que ele pode ver (``shortname``, ``name``, ``type``, ``value`` e ``displayvalue``). Itens que não existem ou que o usuário não pode ver aparecem em ``warnings``. Os plugins de área podem manter um web service próprio e reaproveitar ``local_fieldssuite\external\get_values::get_instances_values()``.
