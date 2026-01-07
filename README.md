#![formationEnLigne]

# formationEnLigne — Plateforme LMS centrée sur les épisodes (vidéos)

Description
- Objectif : une plateforme d'apprentissage où la vidéo (Episode) est l'unité centrale. Les instructeurs créent des `Course` composées d'`Episode` (vidéos). Chaque épisode peut avoir des discussions, des assignments, des soumissions et une notation.

Stack technique
- Backend : Laravel 11 (PHP 8.4)
- Frontend : Vue 3 + Inertia.js
- Base de données : SQLite (développement)
- Auth : Laravel Sanctum + Jetstream

Fonctionnalités principales
- **Rôles** : `admin`, `instructor`, `student`.
- **Gestion des cours** : création, publication, modification par les instructeurs.
- **Episode-centric** : l'épisode (vidéo) est l'unité pédagogique principale.
- **Discussions** : forum Q&A par épisode avec réponses et marquage de solution.
- **Assignments & Submissions** : travaux, soumissions et notation (`Grade`).
- **Progression** : suivi `Progress` et complétion d'épisodes.
- **Reporting** : modération des épisodes via `reported_episodes`.

Architecture (résumé)
- Models clés : `Course`, `Episode`, `Discussion`, `DiscussionReply`, `Assignment`, `Submission`, `Grade`, `Progress`, `ReportedEpisode`.
- Policies & Gates : `CoursePolicy`, `EpisodePolicy`, `DiscussionPolicy`, gate `manage-reports`.
- Middleware : `CheckRole` (supporte rôles combinés, ex. `instructor|admin`).
- Frontend : pages Vue sous `resources/js/Pages/Courses/` (ex. `my-courses.vue`, `show.vue`, `DiscussionForum.vue`).

Installation locale (rapide)
1. Cloner le repo
	- `git clone <repo>`
2. Installer dépendances PHP
	- `composer install`
3. Copier l'environnement et générer la clé
	- `cp .env.example .env`
	- `php artisan key:generate`
4. Configurer la base de données (par défaut SQLite dans `.env`)
	- `touch database/database.sqlite`
	- mettre `DB_CONNECTION=sqlite` dans `.env`
5. Lancer les migrations et seeders
	- `php artisan migrate --seed`
6. Installer dépendances JS et compiler
	- `npm install`
	- `npm run dev`    # développement (watch)
	- `npm run build`  # production
7. Lancer le serveur
	- `php artisan serve`

Tests
- Lancer la suite de tests : `./vendor/bin/phpunit` ou `php artisan test`.

Usage rapide
- Dashboard : `/dashboard`
- Mes formations (instructor) : `/my-courses`
- Créer une formation : `/courses/create`
- Voir une formation (lecteur + discussions) : `/courses/{id}`

Développement Frontend
- Les composants Vue se trouvent sous `resources/js/Pages/`.
- Pour recompiler en développement : `npm run dev`.

Fichiers importants
- `app/Models/Episode.php` — relations : `discussions()`, `assignments()`, `completedBy()`.
- `app/Http/Controllers/CourseController.php` — gestion des cours et épisodes.
- `resources/js/Pages/Courses/show.vue` — lecteur vidéo + intégration discussions.
- `resources/js/Pages/Courses/DiscussionForum.vue` — composant d'exemple pour discussions.

Notes
- La plateforme a été réorientée pour placer l'épisode comme unité pédagogique. Les migrations et modèles pour discussions, assignments, submissions, grades et progression ont été ajoutés.
- Frontend : recompilation nécessaire après modifications (`npm run dev`).

Contribution
- Ouvrez des issues pour bugs/feature requests.
- Utilisez des branches `feature/*` ou `fix/*` et proposez des PRs avec descriptions claires.

Licence
- Voir le fichier `LICENSE`.

---

---

**Copyright**: © yaataldigital 2026

<details>
<summary>Section cachée</summary>

Informations internes : contenu et propriété intellectuelle détenus par yaataldigital. Ne pas supprimer ni modifier sans accord explicite.

</details>

Si les développeurs Laravel devaient construire des SPA web avec Vue avant Inertia, ils devaient configurer des API et renvoyer des données JSON avec Laravel, puis utiliser quelque chose comme AXIOS pour récupérer les données dans les composants Vue. Ils auraient également besoin de quelque chose comme Vue Router pour gérer les routes, ce qui signifierait perdre le routage Laravel, ainsi que les middlewares et les contrôleurs.

Inertia.js, en revanche, permet aux développeurs de construire des applications modernes à page unique Vue, React et Svelte en utilisant un routage et des contrôleurs classiques côté serveur. Inertia a été conçu pour les développeurs de Laravel, Ruby on Rails et Django afin de leur permettre de créer des applications sans modifier leurs techniques de codage consistant à créer des contrôleurs, à extraire des données d’une base de données et à rendre des vues

Grâce à Inertia.js, les développeurs Laravel se sentiront comme chez eux.

##fonctionnement de inertia

Les applications JavaScript à la manière du monolithe
Inertia est une nouvelle approche pour créer des applications Web classiques pilotées par serveur. Nous l'appelons le monolithe moderne.

Inertia vous permet de créer des applications d'une seule page entièrement rendues côté client, sans la complexité associée aux SPA modernes. Pour ce faire, il exploite les modèles côté serveur existants que vous aimez déjà.

Inertia n'a pas de routage côté client et ne nécessite pas d'API. Construisez simplement des contrôleurs et des pages vues comme vous l'avez toujours fait ! Inertia fonctionne très bien avec n'importe quel framework backend, mais il est affiné pour Laravel.

Pas un cadre
Inertia n'est pas un framework, ni un remplacement pour vos frameworks existants côté serveur ou côté client. Au contraire, il est conçu pour fonctionner avec eux. Considérez l'inertie comme une colle qui relie les deux. Inertia le fait via des adaptateurs. Nous avons actuellement trois adaptateurs officiels côté client (React, Vue et Svelte) et deux adaptateurs côté serveur (Laravel et Rails).

Prochaines étapes
Vous voulez en savoir un peu plus avant de plonger ? Découvrez les pages pour qui est-ce et comment ça marche. Ou, si vous êtes prêt à commencer, passez directement aux instructions d'installation.

##tailwindCss

Un cadre CSS utilitaire d'abord rempli de classes telles que flex, pt-4(padding top), text-center et rotate-90 qui peuvent être composées pour créer n'importe quelle conception, directement dans votre balisage.

Tailwind CSS fonctionne en analysant tous vos fichiers HTML, composants JavaScript et tout autre modèle pour les noms de classe, en générant les styles correspondants, puis en les écrivant dans un fichier CSS statique.
It's fast, flexible, and reliable — with zero-runtime.

