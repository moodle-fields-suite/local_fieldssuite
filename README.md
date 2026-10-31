# moodle-local_fieldssuite

[English](#english) · [Português (Brasil)](#português-brasil)

Documentation / Documentação: <https://moodle-fields-suite.github.io/local_fieldssuite/>

## English

Common code of the custom field areas of the [Moodle Fields Suite](https://moodle-fields-suite.github.io): the base class of an area (locked and visibility settings, permission checks), the common pages that manage the fields and edit the values, the display of values on core pages and the `local_fieldssuite_get_values` web service. It does nothing on its own: install it together with an area plugin, such as `tool_categorycustomfields`. Supports Moodle 4.5 LTS and 5.3 LTS.

Install: copy this repository to `local/fieldssuite` (`public/local/fieldssuite` on Moodle 5.1+) and visit *Site administration > Notifications*.

## Português (Brasil)

Código comum das áreas de campos personalizados da [Moodle Fields Suite](https://moodle-fields-suite.github.io): a classe base de uma área (configurações *Bloqueado* e *Visibilidade*, checagem de permissões), as páginas comuns que gerenciam os campos e editam os valores, a exibição dos valores em páginas do Moodle e o web service `local_fieldssuite_get_values`. Sozinho ele não faz nada: instale-o junto com um plugin de área, como o `tool_categorycustomfields`. Suporta Moodle 4.5 LTS e 5.3 LTS.

Instalação: copie este repositório para `local/fieldssuite` (`public/local/fieldssuite` no Moodle 5.1+) e acesse *Administração do site > Notificações*.

## Development / Desenvolvimento

- JavaScript sources are in `amd/src`; rebuild `amd/build` with `npx grunt amd --root=public/local/fieldssuite` in a Moodle checkout.
- CI (`.github/workflows/ci.yml`) runs `moodle-plugin-ci` on Moodle 4.5 and 5.3 with PostgreSQL and MariaDB; run it locally with [`act`](https://github.com/nektos/act) before pushing.

## Support and privacy / Suporte e privacidade

- Issue tracker / Rastreador de issues: <https://github.com/moodle-fields-suite/local_fieldssuite/issues>
- Privacy / Privacidade: the plugin stores nothing on its own; each area plugin declares the data of its entity / o plugin não guarda nada por conta própria; cada plugin de área declara os dados da sua entidade.
- Translations / Traduções: only English is shipped; other languages go through AMOS / o pacote traz só inglês; outros idiomas via AMOS.

## License / Licença

GNU GPL v3 or later. See [LICENSE](LICENSE).
