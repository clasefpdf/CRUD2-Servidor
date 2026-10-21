# Tasques pendents de SprintBoard

Les tasques estan ordenades aproximadament de menor a major complexitat. Les primeres completen la gestió bàsica, després venen els exercicis `TODO` de `REQUIREMENTS.md` i, finalment, les ampliacions de fases posteriors. El fitxer públic continua coordinant la petició i l'SQL, i les plantilles només presenten les dades.

## 1. Creació senzilla d'usuaris

- **Fitxer:** `public/user-create.php`, `public/teams.php`.
- **Objectiu:** Permetre al professorat donar d'alta un alumne que puga iniciar sessió.
- **Tasques:** Crear un formulari amb nom, usuari, correu i contrasenya; validar camps i unicitat del nom d'usuari, guardar el rol `student` i la contrasenya amb `password_hash`, protegir el POST amb CSRF i impedir l'accés a l'alumnat.
- **Conceptes:** Formularis, validació, restriccions `UNIQUE`, `password_hash`, autorització, CSRF.
- **Pàgina de referències:** `public/task-create.php` (alta validada i SQL preparat), `public/login.php` (autenticació).
- **Criteri de finalització:** El professorat crea un alumne que pot iniciar sessió; noms d'usuari repetits o enviaments d'alumnes no creen comptes.

## 2. Crear un esprint

- **Fitxer:** `public/sprint-create.php`, `public/sprints.php`.
- **Objectiu:** Permetre al professorat donar d'alta un esprint amb nom, objectiu, dates i estat.
- **Tasques:** Preparar un formulari, validar camps, dates ordenades i estat permés; guardar amb SQL preparat, CSRF i redirecció amb missatge; reservar esta operació al professorat.
- **Conceptes:** Formularis POST, validació de dates, PDO, autorització, CSRF.
- **Pàgina de referències:** `public/task-create.php`.
- **Criteri de finalització:** El nou esprint apareix al llistat amb les dades introduïdes; dates incorrectes i POST d'un alumne són rebutjats.

## 3. Separar les funcions de missatges flaix de la presentació

- **Fitxer:** `includes/flash.php`, `includes/header.php`, `templates/layout/flashes.php`.
- **Objectiu:** Mostrar els missatges flaix sense generar HTML des de les funcions que els gestionen.
- **Tasques:** Mantindre la funció que afegix missatges i crear-ne una que els retorne i els elimine de la sessió. Presentar les alertes Bootstrap en una plantilla i escapar-hi els missatges, conservant el comportament després de les redireccions.
- **Conceptes:** Responsabilitat de les funcions, sessions, retorn d'arrays, plantilles PHP i escapament HTML.
- **Pàgina de referències:** `public/task.php` i `templates/tasks/show.php` (la pàgina prepara els comentaris i la plantilla els mostra).
- **Criteri de finalització:** Els missatges apareixen després de redirigir, el contingut HTML no s'executa i cada missatge es mostra només una vegada.

## 4. Carregar les dades de la tasca en l'edició

- **Fitxer:** `public/task-edit.php`, `templates/tasks/edit.php`.
- **Objectiu:** Mostrar un formulari d'edició amb les dades actuals de la tasca.
- **Tasques:** Recuperar la tasca accessible i les opcions necessàries, i substituir el text provisional per un formulari emplenat.
- **Conceptes:** PDO, consultes preparades, formularis, escapament HTML.
- **Pàgina de referències:** `public/task.php`, `templates/tasks/create.php`.
- **Criteri de finalització:** Una tasca existent mostra els seus valors; un identificador inaccessible o inexistent retorna 404.

## 5. Filtre del tauler per estat

- **Fitxer:** `public/board.php`, `templates/tasks/board.php`.
- **Objectiu:** Limitar les tasques visibles als estats seleccionats.
- **Tasques:** Afegir el control GET, validar-ne el valor i combinar-lo amb el filtre per equip i la restricció d'accés actual.
- **Conceptes:** `$_GET`, llistes de valors permesos, SQL preparat.
- **Pàgina de referències:** `public/board.php` (filtre per equip).
- **Criteri de finalització:** L'estat triat només mostra tasques d'eixe estat i no permet veure tasques alienes.

## 6. Filtre del tauler per responsable

- **Fitxer:** `public/board.php`, `templates/tasks/board.php`.
- **Objectiu:** Mostrar només les tasques assignades a l'alumne seleccionat.
- **Tasques:** Afegir un selector GET de responsables i combinar-lo amb els altres filtres sense eliminar el control d'accés.
- **Conceptes:** Relacions SQL, filtres combinats, validació d'identificadors.
- **Pàgina de referències:** `public/board.php` (filtre per equip).
- **Criteri de finalització:** Filtrar per responsable funciona sol i combinat amb equip i estat.

## 7. Editar un esprint

- **Fitxer:** `public/sprint-edit.php`, `public/sprint.php`.
- **Objectiu:** Permetre al professorat corregir les dades i l'estat d'un esprint existent.
- **Tasques:** Carregar les dades per identificador, mostrar-les en un formulari, validar els canvis i actualitzar amb SQL preparat i CSRF; mantindre els valors introduïts en cas d'error.
- **Conceptes:** `$_GET`, formularis emplenats, `UPDATE`, validació, autorització.
- **Pàgina de referències:** `public/task.php` (càrrega per identificador), `public/board.php` (actualització protegida).
- **Criteri de finalització:** Els canvis vàlids es veuen en la fitxa; un identificador inexistent retorna 404 i l'alumnat no pot editar.

## 8. Validar l'edició de la tasca

- **Fitxer:** `public/task-edit.php`, `templates/tasks/edit.php`, `src/Validation/TaskValidator.php`.
- **Objectiu:** Mostrar errors d'edició sense perdre els valors introduïts.
- **Tasques:** Reutilitzar les regles aplicables de creació i verificar que equip i responsable corresponen a l'esprint seleccionat.
- **Conceptes:** Validació, reutilització de classes, valors antics de formulari.
- **Pàgina de referències:** `public/task-create.php`.
- **Criteri de finalització:** Les dades invàlides no es guarden i el formulari conserva els valors enviats.

## 9. Guardar els canvis d'una tasca

- **Fitxer:** `public/task-edit.php`.
- **Objectiu:** Persistir una edició vàlida i confirmar-la a l'usuari.
- **Tasques:** Actualitzar amb SQL preparat i CSRF, tornar a comprovar l'accés a la tasca en el POST i redirigir amb un missatge flaix.
- **Conceptes:** PDO, autorització, CSRF, patró POST/redirect/GET.
- **Pàgina de referències:** `public/board.php`, `public/task-create.php`.
- **Criteri de finalització:** Després de guardar, la fitxa mostra els canvis; una persona sense accés no pot editar la tasca.

## 10. Esborrar un esprint

- **Fitxer:** `public/sprint-delete.php`, `public/sprint.php`.
- **Objectiu:** Permetre al professorat eliminar un esprint sense deixar referències inconsistents.
- **Tasques:** Afegir una acció POST amb CSRF i comprovació del rol; impedir l'esborrament si hi ha tasques o assignacions d'equip vinculades i mostrar-ne el motiu.
- **Conceptes:** `DELETE`, claus foranes, comprovació de dependències, autorització, CSRF.
- **Pàgina de referències:** `public/board.php` (POST protegit); no hi ha cap exemple d'esborrament en el projecte.
- **Criteri de finalització:** Un esprint sense dades vinculades desapareix del llistat; un esprint amb tasques o membres no s'esborra i l'usuari rep un missatge.

## 11. Crear equips per a un esprint

- **Fitxer:** `sql/schema.sql`, `public/teams.php`, `public/team-create.php`.
- **Objectiu:** Poder donar d'alta equips i vincular-los a un esprint encara que no tinguen membres.
- **Tasques:** Afegir una relació explícita entre esprints i equips per a permetre equips buits; preparar un formulari per al professorat que cree un equip per a un esprint sense trencar les assignacions existents de `sprint_team_members`. Decidir si es reutilitzen els noms globals de `teams` segons la restricció `UNIQUE` actual.
- **Conceptes:** Taules d'unió, claus foranes, transaccions, autorització, CSRF.
- **Pàgina de referències:** `public/task-create.php` (alta i selecció d'esprint i equip); no hi ha exemple de creació d'equips.
- **Criteri de finalització:** L'equip creat apareix en l'esprint triat abans d'afegir-hi alumnes; els equips d'altres esprints no canvien.

## 12. Afegir alumnes als equips d'un esprint

- **Fitxer:** `public/team.php`, `public/teams.php`, `sql/schema.sql`.
- **Objectiu:** Assignar manualment alumnes a un equip d'un esprint amb un rol inicial.
- **Tasques:** Mostrar els alumnes disponibles, validar esprint, equip, alumne i rol, i registrar l'assignació en `sprint_team_members` amb POST, CSRF i autorització del professorat; evitar que un alumne pertanga a dos equips en el mateix esprint i fer servir els equips creats en la tasca 11.
- **Conceptes:** Formularis, claus compostes, consultes preparades, restriccions d'unicitat, autorització.
- **Pàgina de referències:** `public/team.php` (consulta de membres), `public/task-create.php` (validació i inserció).
- **Criteri de finalització:** L'alumne apareix amb el seu rol en l'equip de l'esprint triat; una segona assignació al mateix esprint és rebutjada sense alterar l'existent.

## 13. Formulari per a canviar el rol d'un membre

- **Fitxer:** `public/team.php`.
- **Objectiu:** Permetre modificar el rol d'un membre de l'equip en l'esprint actiu.
- **Tasques:** Afegir un formulari amb els rols admesos i processar l'enviament amb comprovació de sessió, CSRF i consulta preparada; restringir el canvi al professorat.
- **Conceptes:** Formularis POST, autorització, CSRF, SQL preparat.
- **Pàgina de referències:** `public/board.php`.
- **Criteri de finalització:** El professorat canvia un rol i el veu actualitzat; l'alumnat no pot modificar-lo.

## 14. Un únic Scrum Master per equip i esprint

- **Fitxer:** `public/team.php`.
- **Objectiu:** Evitar que dos membres d'un mateix equip siguen Scrum Master en el mateix esprint.
- **Tasques:** Comprovar els rols existents abans de guardar el canvi i mostrar un error si ja hi ha un altre Scrum Master; considerar també enviaments consecutius.
- **Conceptes:** Validació de regles, consultes SQL, consistència de dades.
- **Pàgina de referències:** `public/task-create.php`.
- **Criteri de finalització:** Es rebutja el segon Scrum Master sense alterar els rols existents.

## 15. Històric de rols per esprint

- **Fitxer:** `public/history.php`.
- **Objectiu:** Consultar quin rol tenia cada alumne en cada esprint.
- **Tasques:** Relacionar esprints, usuaris i `sprint_team_members` i presentar els resultats agrupats per esprint.
- **Conceptes:** JOIN, relacions molts-a-molts, eixida HTML segura.
- **Pàgina de referències:** `public/team.php`.
- **Criteri de finalització:** L'històric mostra els rols dels esprints anteriors, no sols els de l'actiu.

## 16. Històric de tasques completades per alumne

- **Fitxer:** `public/history.php`.
- **Objectiu:** Consultar quantes tasques té completades cada alumne en cada esprint.
- **Tasques:** Comptar tasques `done` per responsable i esprint, incloent-hi alumnat sense tasques completades.
- **Conceptes:** JOIN, `COUNT`, `GROUP BY`, valors zero.
- **Pàgina de referències:** No hi ha cap exemple de recomptes agrupats per alumne i esprint en el projecte.
- **Criteri de finalització:** La xifra coincideix amb les tasques completades assignades a cada alumne en cada esprint.

## 17. Pujar una evidència d'una tasca

- **Fitxer:** `public/evidences.php`, `sql/schema.sql`, `public/task.php`.
- **Objectiu:** Adjuntar un fitxer d'evidència a una tasca accessible.
- **Tasques:** Afegir la relació de dades necessària, un formulari multipart i el processament de pujada amb sessió, CSRF i comprovació d'accés a la tasca; guardar fora de `public/`.
- **Conceptes:** `$_FILES`, formularis multipart, PDO, autorització.
- **Pàgina de referències:** `public/task.php`, `public/task-create.php`.
- **Criteri de finalització:** Una evidència vàlida queda vinculada a la tasca i una persona sense accés no la pot adjuntar.

## 18. Mostrar les evidències d'una tasca

- **Fitxer:** `public/task.php`, `templates/tasks/show.php`, `public/evidences.php`.
- **Objectiu:** Consultar les evidències associades a cada tasca.
- **Tasques:** Recuperar-les amb PDO i mostrar-ne les dades; protegir qualsevol accés al fitxer amb la mateixa autorització que la tasca.
- **Conceptes:** Relacions SQL, autorització, escapament HTML.
- **Pàgina de referències:** `public/task.php` (comentaris).
- **Criteri de finalització:** La fitxa mostra només les evidències pròpies i una URL directa no revela fitxers d'una tasca aliena.

## 19. Validar els fitxers d'evidència

- **Fitxer:** `public/evidences.php`.
- **Objectiu:** Rebutjar fitxers d'un tipus no permés o que superen el límit establit.
- **Tasques:** Definir tipus i mida admesos, comprovar el contingut real del fitxer i mostrar errors abans de moure'l o registrar-lo.
- **Conceptes:** Validació de pujades, tipus MIME, missatges d'error.
- **Pàgina de referències:** `public/task-create.php` (errors de validació); no hi ha exemple de pujada de fitxers.
- **Criteri de finalització:** Un fitxer admés es puja; un fitxer no admés o massa gran no queda accessible ni registrat.

## 20. Nombre de tasques per estat

- **Fitxer:** `public/statistics.php`.
- **Objectiu:** Mostrar la distribució de tasques de l'esprint actiu per estat.
- **Tasques:** Comptar tasques `todo`, `in_progress` i `done`, respectant la visibilitat de l'usuari i mostrant zero quan pertoque.
- **Conceptes:** `COUNT`, `GROUP BY`, autorització en consultes.
- **Pàgina de referències:** No hi ha cap exemple d'agregació SQL per estat en el projecte.
- **Criteri de finalització:** Els tres recomptes coincideixen amb les tasques visibles de l'esprint actiu.

## 21. Tasques completades per equip

- **Fitxer:** `public/statistics.php`.
- **Objectiu:** Comparar el nombre de tasques completades per equip en l'esprint actiu.
- **Tasques:** Relacionar equips i tasques `done` i mostrar també els equips participants sense tasques completades, respectant la visibilitat.
- **Conceptes:** JOIN, agregació SQL, `LEFT JOIN`.
- **Pàgina de referències:** No hi ha cap exemple de recompte per equip en el projecte.
- **Criteri de finalització:** Cada equip visible mostra el recompte correcte, incloent-hi zero.

## 22. Participació per alumne

- **Fitxer:** `public/statistics.php`.
- **Objectiu:** Mostrar les tasques assignades a cada alumne de l'esprint actiu.
- **Tasques:** Relacionar els membres de l'esprint amb les tasques assignades i presentar un recompte per alumne sense duplicats.
- **Conceptes:** JOIN, `GROUP BY`, relacions per esprint.
- **Pàgina de referències:** No hi ha cap exemple de recompte per alumne en el projecte.
- **Criteri de finalització:** Els recomptes reflectixen les assignacions de l'esprint i inclouen els membres sense tasques.

## 23. Percentatge de finalització de l'esprint

- **Fitxer:** `public/statistics.php`.
- **Objectiu:** Mostrar el percentatge de tasques acabades de l'esprint actiu.
- **Tasques:** Calcular la proporció de tasques `done` respecte al total visible i gestionar l'esprint sense tasques o sense esprint actiu.
- **Conceptes:** Agregació, càlcul de percentatges, divisió per zero.
- **Pàgina de referències:** No hi ha cap exemple de càlcul de percentatges en el projecte.
- **Criteri de finalització:** El percentatge és correcte entre 0 i 100 i no falla quan no hi ha tasques.

## 24. Repartiment automàtic d'alumnes

- **Fitxer:** `public/teams.php`, `sql/schema.sql`.
- **Objectiu:** Assignar alumnat als equips d'un esprint concret.
- **Tasques:** Crear una funció de repartiment i un formulari protegit per al professorat que guarde les assignacions en `sprint_team_members`, sense canviar altres esprints.
- **Conceptes:** Funcions, arrays, transaccions, autorització, CSRF.
- **Pàgina de referències:** `public/task-create.php`, `public/team.php`.
- **Criteri de finalització:** El professorat genera equips per a un esprint i els membres apareixen en la fitxa de l'equip.

## 25. Evitar repetir companys de l'esprint anterior

- **Fitxer:** `public/teams.php`.
- **Objectiu:** Reduir les parelles d'alumnes repetides respecte a l'esprint anterior.
- **Tasques:** Consultar les assignacions anteriors i adaptar el repartiment per a prioritzar combinacions noves; definir què passa quan evitar totes les repeticions és impossible.
- **Conceptes:** Consultes SQL, arrays, restriccions d'algorismes.
- **Pàgina de referències:** No hi ha cap exemple d'algorisme de repartiment en el projecte.
- **Criteri de finalització:** Quan hi ha una distribució possible sense repeticions, el nou esprint no repetix parelles.

## 26. Equilibrar la grandària dels equips

- **Fitxer:** `public/teams.php`.
- **Objectiu:** Evitar equips molt desiguals en el repartiment automàtic.
- **Tasques:** Adaptar la funció de repartiment perquè les grandàries diferisquen com a màxim en una persona i mantindre la restricció de companys quan siga possible.
- **Conceptes:** Arrays, repartiment, comprovació de límits.
- **Pàgina de referències:** No hi ha cap exemple de repartiment equilibrat en el projecte.
- **Criteri de finalització:** En generar equips, la diferència entre el més gran i el més menut és com a màxim d'una persona.

## 27. Recuperació de contrasenya (fase posterior)

- **Fitxer:** `public/login.php`, `public/password-reset.php`, `sql/schema.sql`.
- **Objectiu:** Permetre restablir una contrasenya sense conéixer l'anterior.
- **Tasques:** Definir una sol·licitud de recuperació i un token d'un sol ús amb caducitat; validar-lo abans de guardar el nou hash.
- **Conceptes:** Tokens aleatoris, caducitat, `password_hash`, formularis.
- **Pàgina de referències:** `public/login.php`; no hi ha exemple de recuperació de contrasenya.
- **Criteri de finalització:** Un token vigent permet canviar la contrasenya una vegada; un token gastat o caducat no funciona.

## 28. Panell complet d'administració (fase posterior)

- **Fitxer:** `public/admin.php`, `includes/auth.php`.
- **Objectiu:** Oferir al professorat un punt d'accés a la gestió d'usuaris, equips i esprints.
- **Tasques:** Crear una pàgina protegida amb enllaços a les accions administratives disponibles i impedir-hi l'accés a l'alumnat.
- **Conceptes:** Autorització, navegació MPA, HTML segur.
- **Pàgina de referències:** `public/index.php`, `public/board.php`.
- **Criteri de finalització:** El professorat veu el panell i l'alumnat no hi pot entrar per URL directa.

## 29. API REST (fase posterior)

- **Fitxer:** `public/api/tasks.php`.
- **Objectiu:** Consultar les tasques accessibles en format JSON.
- **Tasques:** Crear una entrada PHP que accepte GET, retorne JSON i mantinga les comprovacions de sessió i visibilitat de tasques.
- **Conceptes:** HTTP, JSON, codis d'estat, autorització.
- **Pàgina de referències:** No hi ha cap exemple de resposta JSON en el projecte.
- **Criteri de finalització:** GET retorna JSON amb només les tasques accessibles i una sol·licitud no permesa té un codi HTTP adequat.

## 30. Permisos avançats per rol (fase posterior)

- **Fitxer:** `includes/auth.php`, `public/task-edit.php`.
- **Objectiu:** Diferenciar les accions permeses al professorat i a l'alumnat.
- **Tasques:** Definir una regla concreta d'edició de tasques i aplicar-la tant en la visualització com en el POST, sense substituir el control d'accés actual.
- **Conceptes:** Autenticació, autorització, permisos per operació.
- **Pàgina de referències:** `public/board.php` (actualització d'estat amb permisos).
- **Criteri de finalització:** Una operació restringida funciona per al rol autoritzat i és rebutjada per l'altre encara que envie un POST manual.

## 31. Notificacions (fase posterior)

- **Fitxer:** `public/task.php`, `public/profile.php`, `sql/schema.sql`.
- **Objectiu:** Avisar un usuari quan rep una tasca.
- **Tasques:** Registrar l'avís en assignar la tasca i mostrar els avisos pendents en el perfil de la persona destinatària.
- **Conceptes:** Relacions SQL, esdeveniments de l'aplicació, consultes per usuari.
- **Pàgina de referències:** `public/task-create.php`, `public/profile.php`.
- **Criteri de finalització:** L'usuari assignat veu l'avís al seu perfil i altres usuaris no el veuen.

## 32. Integració amb GitHub (fase posterior)

- **Fitxer:** `public/task.php`, `sql/schema.sql`.
- **Objectiu:** Associar una tasca a un enllaç de GitHub consultable des de la fitxa.
- **Tasques:** Afegir el camp i un formulari amb validació de l'URL; mostrar l'enllaç de manera segura només a qui tinga accés a la tasca.
- **Conceptes:** Validació d'URL, PDO, escapament d'atributs HTML.
- **Pàgina de referències:** `public/task.php`, `public/task-create.php`.
- **Criteri de finalització:** Una URL vàlida de GitHub es pot guardar i obrir; una URL no admesa es rebutja.

## 33. Exportació d'informes (fase posterior)

- **Fitxer:** `public/statistics.php`, `public/export.php`.
- **Objectiu:** Descarregar un informe amb les estadístiques de l'esprint.
- **Tasques:** Reutilitzar les consultes de les mètriques i generar un CSV amb capçaleres HTTP adequades, escapament de camps i permisos equivalents a la pàgina.
- **Conceptes:** CSV, capçaleres HTTP, autorització, reutilització de dades.
- **Pàgina de referències:** No hi ha cap exemple de generació o descàrrega de CSV en el projecte.
- **Criteri de finalització:** Es descarrega un CSV llegible amb les dades visibles de l'esprint i sense informació d'equips aliens.

## 34. Registrar errors en una altra operació

- **Fitxer:** `public/task-create.php`, `includes/log.php`.
- **Objectiu:** Poder diagnosticar una fallada en la creació d'una tasca sense mostrar detalls tècnics a l'usuari.
- **Tasques:** Consultar com `includes/bootstrap.php` registra amb Monolog una excepció de SQLite; aplicar el mateix criteri quan falla la inserció de tasques, sense registrar contrasenyes ni dades innecessàries, i retornar un missatge genèric sense perdre la validació habitual.
- **Conceptes:** Composer, dependències de producció, excepcions, registre d'errors i respostes HTTP.
- **Pàgina de referències:** `includes/bootstrap.php` (captura de l'error, ús de Monolog i resposta genèrica).
- **Criteri de finalització:** Una fallada de persistència deixa una entrada en el registre del servidor i no mostra informació SQL ni una traça en la pàgina; una creació correcta conserva el comportament anterior.
