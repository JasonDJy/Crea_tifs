# CREA'TIFS

Application PHP (MVC, sans framework) — portfolio de projets capillaires.

## Installation

1. Importer `www/documents/db/db_remplie.sql` dans MySQL (crée la base `creatifs`).
2. Copier `www/app/config/params-example.php` en `www/app/config/params.php` et y renseigner les paramètres de connexion.
3. Pointer le serveur (Apache + `mod_rewrite`) sur `www/public/`.

## Routes

| Route | Pattern | Routeur | Contrôleur → action |
|---|---|---|---|
| Liste des projets (défaut) | `/` ou `/projects` | `index` | `projectsController` → `index` |
| Détail d'un projet | `/projets/id/slug.html` | `projects` | `projectsController` → `show` |
| Suppression | `/projets/delete/id/slug.html` | `projects` | `projectsController` → `delete` |
| Ajout : formulaire | `/projects/add/form.html` | `projects` | `projectsController` → `addForm` |
| Ajout : insertion | `/projects/add/insert.html` | `projects` | `projectsController` → `addInsert` |
| Modification : formulaire | `/projects/id/slug/edit/form.html` | `projects` | `projectsController` → `editForm` |
| Modification : update | `/projects/id/slug/edit/update.html` | `projects` | `projectsController` → `editUpdate` |
| Page d'un créa'tif | `/creatifs/id.html` | `creatifs` | `creatifsController` → `show` |
| Projets d'un tag | `/tags/id/slug.html` | `tags` | `tagsController` → `show` |

## Architecture

```
www/
  app/
    config/       params-example.php (params.php est ignoré par git)
    controllers/  pagesController, projectsController, creatifsController, tagsController
    models/       projectsModel, creatifsModel, tagsModel
    routers/      index (dispatcher), projects, creatifs, tags
    views/
      projects/   index, show, addForm, editForm, _form, _card
      creatifs/   show
      pages/      notFound
      templates/  default.php + partials/ (_head, _nav, _hero, _main, _aside,
                  _creatifs, _tags, _pagination, _footer, _scripts)
  core/           init, constantes, connexion, helpers
  documents/      SQL, template d'origine, consignes
  public/         index.php (front controller), .htaccess, css, images, vendor
```

Zones dynamiques du template : `$title` (`_head.php`) et `$content` (`_main.php`),
remplies par les contrôleurs. La sidebar (`_aside.php`) appelle
`CreatifsController\indexAsideAction` et `TagsController\indexAsideAction`.
