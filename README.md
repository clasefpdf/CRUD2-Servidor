# SprintBoard

Aplicació didàctica MPA en PHP 8.3, PDO i SQLite per a gestionar esprints, equips i tasques Kanban. Cada fitxer de `public/` gestiona directament la seua petició; `src/` conté la tasca i la validació reutilitzable.

## Posada en marxa

Cal PHP 8.3 amb `pdo_sqlite` i `mbstring`, i Composer. Des de l'arrel del projecte:

```bash
composer install
php -S localhost:8000 -t public
```

Obri `http://localhost:8000/login.php`. La base SQLite es crea i s'ompli automàticament en la primera petició a `data/sprintboard.sqlite`. Per reinicialitzar les dades de prova, elimina aquest fitxer amb el servidor parat. Usuaris: `admin`, `alice`, `bob`, `carol`, `david` i `eva`; contrasenya: `1234` (només per a l'entorn didàctic).

## Proves automàtiques

PHPUnit és una dependència de desenvolupament instal·lada amb `composer install`. Com en JUnit, les proves s'organitzen en classes amb mètodes `test...` i assercions. Des de l'arrel del projecte, executa:

```bash
./vendor/bin/phpunit
```

Les proves de `tests/Unit/` comproven regles de `Task` i `TaskValidator` sense iniciar el servidor ni crear una base de dades. Per executar només un fitxer: `./vendor/bin/phpunit tests/Unit/TaskTest.php`.

## Registre d'errors

Monolog és una dependència de producció instal·lada amb Composer. `includes/log.php` configura el registrador i `includes/bootstrap.php` l'utilitza per a registrar els errors d'inicialització de SQLite en la sortida d'error del servidor PHP. L'usuari només veu un missatge genèric i el codi HTTP 500; les dades tècniques queden en el registre del servidor.

## Què funciona i què queda per fer

Funcionen el login/logout, el perfil, la consulta d'esprints i equips, el tauler amb filtre per equip i canvis d'estat, la creació validada de tasques i els comentaris. Les pàgines protegides requereixen sessió; els formularis POST tenen CSRF i les eixides HTML s'escapen.

Els `TODO` dels fitxers `public/` indiquen els exercicis pendents: edició de tasques, canvis de rols, filtres addicionals, històric detallat, evidències, estadístiques i assignació automàtica d'equips. El registre, la recuperació de contrasenya, l'API i altres ampliacions queden per a fases posteriors.
