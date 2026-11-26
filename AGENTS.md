# Réaliser le site vitrine d'une église

## 1. Mission

Construire le site vitrine d'une église, dans un projet Laravel 12 **déjà créé**, avec une base MySQL **déjà opérationnelle**. Le site doit être simple, rapide, accessible, utilisable sur téléphone, et déployable sur un hébergement mutualisé gratuit **sans accès SSH**.

Tous les contenus fournis ici (nom, adresse, textes, événements, photos) sont **fictifs**. Ils servent à construire et tester le site. Le propriétaire les remplacera par les vrais contenus. Voir la section 5.

## 2. État de départ (à ne pas refaire)

- Le projet Laravel 12 existe. Ne le recrée pas, ne lance pas `laravel new`.
- La base de données est créée et la connexion fonctionne. Ne supprime jamais de table existante, n'utilise ni `migrate:fresh` ni `db:wipe` sans demander.
- Si une migration a déjà été exécutée, ne la modifie pas : crée une nouvelle migration.
- Avant de commencer, vérifie l'état avec `php artisan about`, `php artisan migrate:status` et `git status`.

## 3. Stack et contraintes techniques

| Élément | Choix imposé |
| --- | --- |
| Backend | Laravel 12, PHP 8.2 (n'utilise aucune fonctionnalité réservée à PHP 8.3 ou plus) |
| Vues | Blade uniquement, avec composants Blade anonymes. Pas de Livewire, Inertia, React ni Vue |
| CSS | Tailwind CSS 4, configuré dans le CSS avec `@theme`. Pas de `tailwind.config.js` |
| Build | Vite 7 (`npm run dev`, `npm run build`) |
| Base | MySQL / MariaDB |
| Langue | Interface en français ; code, noms de classes, variables et routes techniques en anglais |

Contraintes liées à l'hébergement cible (mutualisé gratuit, sans SSH) :

- Aucune fonctionnalité qui dépend d'un worker de queue ou d'un scheduler : `QUEUE_CONNECTION=sync`.
- Sessions et cache : `SESSION_DRIVER=file` ou `database`, `CACHE_STORE=file`.
- Pas de lien symbolique `storage:link`. Les fichiers envoyés vont sur un disque dont la racine est `public_path('uploads')`, déclaré dans `config/filesystems.php`.
- Pas de dépendance lourde. Seule dépendance supplémentaire autorisée sans demander : `intervention/image-laravel` pour le traitement d'images.
- Aucune ressource externe chargée au démarrage (CDN de scripts, de CSS, trackers). Exceptions : les vidéos intégrées par `iframe` depuis un service externe, et éventuellement les polices si le propriétaire les accepte (sinon utilise la pile de polices système).

## 4. Règles de travail

1. Travaille **une tâche à la fois**, dans l'ordre de la section 10. À la fin de chaque tâche : lance les tests, vérifie le critère d'acceptation, propose un message de commit.
2. N'invente pas de données réelles. Utilise uniquement le contenu fictif de la section 5.
3. Si une consigne est ambiguë ou si une décision change l'architecture, pose **une** question courte avant de continuer.
4. Commente le code seulement quand le « pourquoi » n'est pas évident. Garde les classes courtes et les noms explicites.
5. Respecte les conventions Laravel (noms de modèles au singulier, tables au pluriel, Form Requests pour la validation, routes nommées).
6. Explique en deux ou trois phrases ce que tu as fait à la fin de chaque tâche, et ce que le propriétaire doit vérifier à la main.

## 5. Contenu fictif et point unique de remplacement

Centralise l'identité de l'église dans `config/church.php`. Les vues lisent **toujours** ces valeurs avec `config('church.xxx')` : rien d'écrit en dur dans les vues.

Valeurs fictives à utiliser :

```php
return [
    'name' => "Église Communautaire Lumière d'Espérance (nom fictif)",
    'short_name' => "Lumière d'Espérance",
    'tagline' => "Une communauté ouverte à tous, au cœur de la ville.",
    'address' => "12 rue de l'Espérance (adresse fictive)",
    'city' => 'Ville-Exemple',
    'phone' => '+00 00 00 00 00',
    'email' => 'contact@exemple.org',
    'schedules' => [
        ['label' => 'Culte du dimanche', 'day' => 'Dimanche', 'time' => '9h00 – 11h30'],
        ['label' => 'Étude biblique', 'day' => 'Mercredi', 'time' => '18h30 – 20h00'],
        ['label' => 'Réunion de prière', 'day' => 'Vendredi', 'time' => '18h00 – 19h00'],
    ],
    'socials' => ['facebook' => null, 'youtube' => null],
    'donation_url' => 'https://exemple.org/don', // lien externe fictif, voir section 12
    'map_url' => null,
];
```

Textes fictifs de référence :

- **Accueil** : titre « Bienvenue chez nous », sous-titre repris de `tagline`, trois blocs (« Nous rejoindre », « Nos rencontres », « Nous contacter »).
- **À propos** : trois courts paragraphes (histoire fictive : « fondée il y a vingt ans par quelques familles du quartier »), une section « Notre vision » avec trois valeurs : accueil, prière, service.
- **Événements de démonstration** (via seeder) : une soirée de louange, une journée de nettoyage du quartier, un repas communautaire, une conférence pour les familles. Dates relatives à aujourd'hui (`now()->addDays(...)`) pour qu'ils soient toujours à venir.
- **Annonces de démonstration** : trois annonces courtes (changement d'horaire, collecte de vêtements, inscriptions à la chorale).
- **Photos de démonstration** : n'en télécharge pas. Génère des images de remplacement localement (SVG ou GD, couleurs de la charte, texte « Photo 1 »…), réparties dans deux albums fictifs (« Fête de l'été », « Journée solidaire »).
- **Vidéos** : utilise un identifiant factice `VIDEO_ID` dans `https://www.youtube.com/embed/VIDEO_ID`, avec un commentaire indiquant à remplacer. N'affiche pas d'`iframe` cassée : si l'identifiant vaut `VIDEO_ID`, montre un bloc « Vidéo à venir ».
- Évite toute citation religieuse longue ou sous droit d'auteur. Si un texte biblique est nécessaire, un renvoi de référence (par exemple « Psaume 23 ») suffit.

## 6. Structure cible

```
app/
  Http/Controllers/        PageController, EventController, AnnouncementController,
                           AlbumController, ContactController
  Http/Requests/           ContactRequest
  Models/                  Event, Announcement, Album, Photo
  Services/PhotoStorage.php   redimensionnement + conversion WebP
  Mail/ContactMessage.php
config/church.php
database/{migrations,factories,seeders}
resources/
  css/app.css              @import "tailwindcss"; + @theme
  views/
    components/            layouts/app, header, footer, button, section-title,
                           card, event-card, photo-grid, alert
    pages/                 home, about, visit, sermons, donate
    events/ announcements/ gallery/ contact/ errors/404
tests/Feature/
```

## 7. Modèles de données

| Table | Colonnes |
| --- | --- |
| `events` | id, title, description (nullable), starts_at (datetime), location (nullable), is_published (bool, défaut true), timestamps |
| `announcements` | id, title, body, published_at (nullable), is_published (bool), timestamps |
| `albums` | id, title, slug (unique), description (nullable), taken_at (date, nullable), timestamps |
| `photos` | id, album_id (clé étrangère, suppression en cascade), path, caption (nullable), position (entier), timestamps |

Chaque modèle déclare `$fillable` (ou `$guarded` vide justifié) et les casts de dates. Chaque modèle a une factory et un seeder de démonstration. Un scope `published()` filtre le contenu visible ; les événements ont aussi `upcoming()`.

## 8. Pages et routes

| URL | Nom | Contenu |
| --- | --- | --- |
| `/` | `home` | Bandeau, horaires, 3 prochains événements, dernières annonces |
| `/a-propos` | `about` | Histoire, vision |
| `/horaires-et-lieu` | `visit` | Horaires, adresse, lien carte |
| `/evenements` | `events.index` | Événements à venir, paginés |
| `/evenements/{event}` | `events.show` | Détail |
| `/annonces` | `announcements.index` | Annonces publiées |
| `/galerie` | `gallery.index` | Liste des albums |
| `/galerie/{album:slug}` | `gallery.show` | Photos de l'album |
| `/predications` | `sermons` | Vidéos intégrées |
| `/contact` | `contact.show` / `contact.send` | Formulaire (POST limité par `throttle`) |
| `/don` | `donate` | Texte + bouton vers `config('church.donation_url')` |

Une page 404 personnalisée est obligatoire. Utilise `Route::view` pour les pages sans logique.

## 9. Design

- **Style** : sobre, chaleureux, lisible. Beaucoup d'espace, titres nets, photos mises en valeur.
- **Jetons** dans `resources/css/app.css` avec `@theme` (valeurs fictives, modifiables par le propriétaire) :
  - `--color-brand-50` à `--color-brand-900` : une teinte bleu-violet.
  - `--color-accent-500` : une teinte dorée pour les appels à l'action.
  - Fond clair neutre, texte quasi noir.
- **Mobile d'abord** : écris le style de base pour petit écran, puis `md:` et `lg:`.
- **Accessibilité** : contraste suffisant, un seul `<h1>` par page, titres hiérarchisés, `alt` sur chaque image, focus visible, menu utilisable au clavier, `lang="fr"` sur `<html>`.
- Les éléments répétés (bouton, carte, titre de section) sont des composants Blade, jamais du HTML dupliqué.

## 10. Plan d'exécution

Chaque tâche se termine par son critère d'acceptation.

**Tâche 1 : fondations.** Vérifier l'état (section 2). Régler `APP_LOCALE=fr`, le fuseau horaire et `APP_NAME` ; régler sessions, cache et queue (section 3). Créer `config/church.php`. Créer le layout `<x-layouts.app>`, le header (menu avec bouton mobile) et le footer. Route `home`.
*Acceptation* : la page d'accueil s'affiche avec le layout et les données de `config('church')`.

**Tâche 2 : design et composants.** Définir les jetons `@theme`, créer `button`, `section-title`, `card`, `alert`.
*Acceptation* : changer une couleur dans `@theme` la modifie sur tout le site.

**Tâche 3 : pages statiques.** Accueil complète, À propos, Horaires et lieu, Prédications, Don (lien externe seulement).
*Acceptation* : toutes les pages sont reliées par le menu, sans texte en dur dupliqué, et lisibles sur 360 px de large.

**Tâche 4 : événements et annonces.** Migrations, modèles, factories, seeders, contrôleurs, vues, pagination, dates formatées en français (`translatedFormat`).
*Acceptation* : la page Événements liste les événements futurs triés par date, masque les non publiés, et affiche un message clair quand il n'y en a aucun.

**Tâche 5 : galerie.** Migrations et modèles `Album` et `Photo`, service `PhotoStorage` (largeur maximale environ 1600 px, WebP, miniatures), disque `uploads`, seeders avec images de remplacement générées localement, grille avec `loading="lazy"`.
*Acceptation* : un album affiche ses miniatures ; une image source de 5 Mo ressort allégée (quelques centaines de Ko).

**Tâche 6 : contact.** `ContactRequest` (nom, e-mail valide, message de longueur bornée), `Mailable`, `@csrf`, champ honeypot, `throttle`, message de confirmation. `MAIL_MAILER=log` en développement.
*Acceptation* : un formulaire invalide garde la saisie et montre les erreurs ; un formulaire valide écrit le message dans `storage/logs/laravel.log`.

**Tâche 7 : SEO et finitions.** `<title>` et description par page, balises Open Graph, favicon, `robots.txt`, page 404, plan du site simple.
*Acceptation* : chaque page a un titre et une description uniques.

**Tâche 8 : tests.** Voir la section 11.

**Tâche 9 : préparation du déploiement.** Rédiger `DEPLOY.md` : commandes de build (`composer install --no-dev --optimize-autoloader`, `npm run build`), export de la base, structure à envoyer sur l'hébergeur (seul `public/` accessible), valeurs de `.env` de production, droits d'écriture sur `storage/` et `bootstrap/cache/`. Ne déploie rien toi-même.
*Acceptation* : un lecteur qui ne connaît pas le projet peut suivre `DEPLOY.md` sans question.

## 11. Tests exigés

Écris-les avec PHPUnit ou Pest, selon ce que le projet utilise déjà, et lance `php artisan test`.

- Chaque route publique répond 200 ; une URL inconnue répond 404.
- Les événements non publiés ou passés n'apparaissent pas dans la liste.
- Le formulaire de contact refuse un e-mail invalide et accepte une saisie correcte.
- Le service `PhotoStorage` produit une image WebP de largeur au plus 1600 px.
- Le champ honeypot rempli fait rejeter le message.

## 12. Sécurité et interdits

- **Aucun code de paiement.** La page de don renvoie vers un lien externe. Ne stocke jamais de numéro de carte ni de données bancaires.
- N'ajoute aucun secret dans le dépôt. `.env` reste hors de Git ; fournis `.env.example` à jour.
- Ne mets jamais `APP_DEBUG=true` dans une configuration de production ou dans la documentation de déploiement.
- Toute donnée reçue est validée côté serveur. Tous les formulaires contiennent `@csrf`. N'utilise `{!! !!}` que pour du contenu que tu as toi-même assaini.
- Valide chaque fichier envoyé (type image, taille maximale).
- Pas de scripts de suivi, pas de cookies non nécessaires.
- N'installe aucune autre dépendance sans en expliquer la raison et attendre l'accord du propriétaire.

## 13. Définition de « terminé »

- [ ] Toutes les pages du tableau de la section 8 existent et sont accessibles par le menu
- [ ] Aucun contenu d'identité écrit en dur dans les vues ; tout vient de `config/church.php` ou de la base
- [ ] `php artisan migrate` et `php artisan db:seed` fonctionnent depuis une base vide
- [ ] `npm run build` réussit sans avertissement bloquant
- [ ] `php artisan test` passe
- [ ] Site correct sur 360 px, 768 px et 1280 px de large
- [ ] Contraste, titres, `alt` et navigation clavier vérifiés
- [ ] `DEPLOY.md` rédigé
- [ ] Liste écrite de tout ce qui est fictif et à remplacer (section 14)

## 14. Remplacer le contenu fictif

À la fin du travail, fournis au propriétaire une liste de remplacement :

1. Les valeurs de `config/church.php` (nom, adresse, horaires, contacts, lien de don, réseaux).
2. Le seeder de démonstration : à ne pas exécuter en production ; les vrais événements et annonces seront saisis ensuite.
3. Les images de remplacement : les supprimer de `public/uploads` et les remplacer par les vraies photos, envoyées via le service `PhotoStorage`.
4. L'identifiant `VIDEO_ID` des vidéos.
5. Les textes de « À propos » et de l'accueil.
6. Les couleurs et polices (`@theme`), le logo et le favicon.
