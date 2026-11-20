# simplesamlphp-module-startpage

A SimpleSAMLphp module adding basic listing for service providers and allowing IdP initiated login.

Version 3.x requires SimpleSAMLphp 2.5 or newer and PHP 8.3 or newer. Use 2.x releases with SimpleSAMLphp 1.x.

## Usage

Enable the module with `'module.enable' => ['startpage' => true]` in `config/config.php`.
The listing is available at `module.php/startpage/splist.php`; to use it as the front page set
`'frontpage.redirect'` to that URL.

Service providers are listed when their remote metadata has `startpage.link` set; `startpage.logo`
(a file name under `/userlogos/`) and `name` are optional.

Options in `config/module_startpage.php`: `pageTitle`, `pageSubtitle`, `showLogout`, `authenticate`
(require login with the default hosted IdP's auth source, default `true`).
