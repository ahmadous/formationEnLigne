# Plan: Migrations et Tests

## Migrations
- [x] Examiner les migrations existantes (courses, episodes, completions)
- [ ] Vérifier que les migrations sont à jour avec Laravel 11

## Tests Unitaires - Modèles
- [ ] Créer `tests/Unit/Models/CourseTest.php`
  - [ ] Test: Création d'un course avec factory
  - [ ] Test: Relation episodes
  - [ ] Test: Relation user
  - [ ] Test: Fillable attributes
  - [ ] Test: Accesseur permission

- [ ] Créer `tests/Unit/Models/EpisodeTest.php`
  - [ ] Test: Création d'un episode avec factory
  - [ ] Test: Relation course
  - [ ] Test: Fillable attributes

## Tests Fonctionnels - Courses
- [ ] Créer `tests/Feature/CoursesTest.php`
  - [ ] Test: Liste des cours visible par tous (route /courses)
  - [ ] Test: Détail d'un cours visible par tous (route /course/{id})
  - [ ] Test: Page de création accessible (authentifié)
  - [ ] Test: Création de cours avec épisodes (authentifié)
  - [ ] Test: Modification de cours (authentifié + propriétaire)
  - [ ] Test: Épisode toggle progress (authentifié)

## Exécution des Tests
- [ ] Exécuter `php artisan test`
- [ ] Vérifier que tous les tests passent

