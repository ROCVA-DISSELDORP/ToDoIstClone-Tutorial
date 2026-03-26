## Overzicht
Dit project is een eenvoudige “Todoist”-achtige app in PHP met MySQL. De database tabellen en een groot deel van de server-logica staan al klaar in:

- `sql/schema.sql` (tabellen + proefdata)
- `app/Controllers/AuthController.php` (registreren/inloggen/uitloggen + login-check)
- `app/Controllers/TaskController.php` (taken ophalen + maken + status wisselen + bewerken + verwijderen)
- `app/Controllers/ProjectController.php` (projecten ophalen + maken + bewerken + verwijderen)

Wat nog ontbreekt voor een werkende app is vooral de “koppeling” tussen:

- de entrypoints in `public/` (`login.php`, `register.php`, `logout.php`) die nu leeg zijn
- de UI in `public/index.php` die nu vooral statische HTML is met commentaar (“PHP LOOP”)
- de controllers die de echte data-acties doen

Deze instructies beschrijven wat je moet bouwen/afronden en geven hints over waar je dat doet. Er staan bewust geen codefragmenten in.

## 1. Database instellen
1. Zorg dat MySQL draait.
2. Importeer `sql/schema.sql` in je MySQL server.
3. Controleer `config/database.php`:
   - dat hostnaam, databasenaam, gebruikersnaam en wachtwoord kloppen met jouw MySQL setup
   - dat de database daadwerkelijk bestaat (het schema maakt `todoist_db` aan als het nog niet bestaat)

## 2. Begrijp het datamodel
Uit `sql/schema.sql`:
- `users`
  - `name`, `email` (uniek), `password` (BCRYPT hash)
- `projects`
  - hoort bij een `user_id`
  - `name` en `color`
- `tasks`
  - hoort bij een `user_id`
  - `project_id` is optioneel (kan NULL)
  - `title`, `description`, `due_date`
  - `is_completed` (boolean)

Belangrijke business-regels die je UI moet respecteren:
- Taken met `project_id = NULL` gedragen zich als “Inbox”.
- “Vandaag” is een filter op `due_date` die gelijk is aan de huidige datum.
- Bij het verwijderen van een project zet de database `tasks.project_id` op NULL (dus taken blijven bestaan als “Inbox”, niet als project).

## 3. Authenticatie (login/register/logout)
### 3.1 `public/login.php` (staat nu leeg)
Wat je moet doen:
1. Toon een inlogformulier (email + wachtwoord).
2. Bij submit:
   - haal input server-side op
   - roep de `login(...)` logica aan uit `app/Controllers/AuthController.php`
   - bij succes: markeer de sessie en stuur door naar de plek waar je taken beheert (meestal `public/index.php`)
   - bij falen: toon een foutmelding zonder details prijs te geven (bijv. “inloggen mislukt”)

Extra hint:
- Zorg dat je `login.php` ook aanstuurt dat een gebruiker die al ingelogd is niet opnieuw hoeft in te loggen (optioneel, maar netter).

### 3.2 `public/register.php` (staat nu leeg)
Wat je moet doen:
1. Toon een registratieformulier (naam + email + wachtwoord).
2. Bij submit:
   - valideer dat de invoer compleet is
   - roep `register(...)` aan uit `AuthController`
   - bij database-conflict (zoals “email bestaat al”): toon een vriendelijke melding
   - daarna kun je kiezen:
     - óf doorsturen naar `login.php`
     - óf (als je dat wilt) direct inloggen (maar dat is een extra flow)

Extra hint:
- De hashing van wachtwoorden gebeurt al in de controller. Focus dus op validatie en flow.

### 3.3 `public/logout.php` (staat nu leeg)
Wat je moet doen:
1. Implementeer een “uitloggen” endpoint.
2. Bij aanroep:
   - roep de `logout()` logica aan (sessie vernietigen)
   - stuur daarna door naar `login.php`

## 4. Login-check voor de app-pagina
In `app/Controllers/AuthController.php` zit `checkLoggedIn()`.
Je moet ervoor zorgen dat iedere pagina die taken/projecten laat zien dit bewaakt, met als belangrijkste kandidaat `public/index.php`.

Hint:
- Voer de check zo vroeg mogelijk uit, voordat je HTML rendert.
- Als de gebruiker niet ingelogd is, moet hij/zij naar `login.php` worden gestuurd.

## 5. `public/index.php`: projecten en taken “dynamisch” maken
`public/index.php` is nu vrijwel puur HTML met placeholders:
- “Gebruik getProjects()…” (maar er is geen `getProjects()` zoals naam; projecten ophalen zit in `ProjectController::getAllProjects(...)`)
- “Gebruik getTasks()…” (taken ophalen zit in `TaskController::getTasks(...)`)

### 5.1 Sidebar: projectlijst renderen
Wat je moet bouwen:
1. Haal in `index.php` de huidige gebruiker uit de sessie (via `user_id`).
2. Roep `getAllProjects(userId)` aan.
3. Render de sidebar links op basis van die projecten:
   - toon `name`
   - gebruik `color` als dot/kleurtje

Wat je moet ook beslissen:
- Hoe kies je “welk project” de hoofdtekst/taaklijst toont?
  - Gebruik idealiter een simpele manier zoals “keuze via queryparameter” of “keuze via URL pad”.
  - Voor “Inbox” gebruik je een vaste selector (in de controller heet dat conceptueel `inbox`).

### 5.2 “Vandaag” als filter
In de UI staat een “Vandaag”-link. Dat moet aan een server-side filter gekoppeld worden:
1. Als “Vandaag” actief is, roep je `getTasks(userId, projectSelector, onlyToday=true)` aan.
2. Zet de state zo dat je na acties (taak toevoegen/toggle) terugkeert naar dezelfde filter.

### 5.3 Taaklijst renderen
Wat je moet bouwen:
1. Roep `getTasks(...)` aan met de juiste parameters:
   - `userId` uit de sessie
   - project filter (NULL voor “alles”, of specifiek project, of “inbox”)
   - `onlyToday` afhankelijk van de UI-keuze
2. Render per taak:
   - titel
   - due_date (alleen tonen als die gevuld is)
   - status (voltooid of niet)

Hint over status:
- In `TaskController::getTasks` staat een sortering waarbij onvoltooid vooraan komt. Zorg dat je checkbox/knop exact met `is_completed` overeenkomt.

## 6. Acties in de UI: taak toevoegen en status wisselen
### 6.1 Taak toevoegen (“Toevoegen” knop)
De form in `index.php` is nu alleen visueel. Voor een werkende flow moet je:
1. Bepalen welke velden je accepteert:
   - minimaal: `title` en `due_date` (in de UI zitten die al)
   - optioneel: `description` (staat niet in het formulier, maar mag later)
2. Het formulier moet naar een server-afhandelingsroute gaan (meestal een endpoint of dezelfde pagina met een POST flow).
3. In de afhandelingslogica:
   - roep `createTask(userId, title, description, dueDate, projectId)` aan
   - zet `projectId` correct:
     - als je “Inbox” toevoegt: project_id moet NULL worden
     - als je project geselecteerd hebt: gebruik de project id
4. Redirect/refresh daarna naar de taakweergave met dezelfde filter (anders lijkt het alsof het niet werkt).

Extra hint:
- Valideer due_date zodat het een geldige datum is voordat je hem opslaat.

### 6.2 Status wisselen (checkbox/cirkel knop)
In de taaklijst zit een knop die bedoeld is om “voltooid/niet voltooid” te togglen.
Voor een werkende flow:
1. Laat elke taak-knop een “taak-id” meesturen.
2. Zet dat om naar een server-aanroep op een endpoint die:
   - `toggleTaskStatus(taskId, userId)` gebruikt
3. Redirect terug naar dezelfde lijst/filter.

Hint:
- Omdat de controller het via `user_id` afbakent in de query, voorkom je dat gebruikers elkaars taken kunnen togglen (zolang je het taskId correct doorgeeft).

## 7. Taak bewerken/verwijderen (indien je UI dit nog niet heeft)
Er staan controllers voor:
- `updateTask(...)`
- `deleteTask(...)`

Dus je kunt ze pas volledig benutten als je de UI ook bouwt met:
1. een “edit” mechanisme (modal of aparte pagina)
2. een “delete” mechanisme (met bevestiging is handig)

Implementatie-hint (zonder code):
- Voor edit heb je minstens: huidige velden uitlezen, gebruiker wijzigt, en daarna `updateTask` aanroepen met de nieuwe waarden.
- Voor delete heb je: taak-id verzamelen en bevestigen, en daarna `deleteTask` aanroepen.

## 8. Validatie, foutmeldingen en randgevallen
Minimale hints die je app stabiel maken:
1. Valideer server-side altijd opnieuw (nooit alleen in de browser).
2. Titels mogen niet leeg zijn.
3. due_date moet een geldige datum zijn of mag leeg (als je dat ondersteunt).
4. description mag leeg zijn (schema laat TEXT toe).
5. Geef bij auth fouten dezelfde generieke melding, zodat je niet verraadt of email bestaat.

## 9. Snelle testcases (handmatig)
Na je afronding kun je deze sanity checks doen:
1. Start de app, probeer eerst `index.php` zonder login: je hoort naar `login.php` doorgestuurd te worden.
2. Log in met de testgebruiker uit `sql/schema.sql`.
3. Controleer de sidebar:
   - je ziet meerdere projecten
   - kleuren komen overeen met de database
4. Voeg een taak toe met due_date = vandaag:
   - de taak verschijnt bij “Vandaag”
5. Toggle een taak:
   - status verandert
   - sortering (onvoltooid vooraan) klopt
6. Verwijder een project:
   - taken blijven bestaan en worden “Inbox” (project_id NULL) door de foreign key gedrag.
