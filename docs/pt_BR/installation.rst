Instalação
==========

Requisitos
----------

* Moodle 4.5 LTS ou 5.3 LTS.
* PHP suportado pela sua versão do Moodle.

Os plugins de área da suíte declaram este plugin como dependência: o Moodle pede por ele quando você instala um deles.

A partir de um arquivo ZIP
--------------------------

1. Baixe ``local_fieldssuite-<versão>.zip`` nas `releases <https://github.com/moodle-fields-suite/local_fieldssuite/releases>`_.
2. Acesse *Administração do site > Plugins > Instalar plugins*, envie o ZIP e siga os passos.
3. Conclua a atualização em *Administração do site > Notificações*.

A partir do código-fonte
------------------------

Copie o repositório para ``local/fieldssuite`` (``public/local/fieldssuite`` no Moodle 5.1 ou posterior) e acesse *Administração do site > Notificações*:

.. code-block:: bash

   git clone https://github.com/moodle-fields-suite/local_fieldssuite.git public/local/fieldssuite

Idiomas
-------

O pacote traz apenas as strings em inglês. Os demais idiomas, inclusive o português do Brasil, vêm da ferramenta de tradução do Moodle (AMOS), pelos pacotes de idioma.
