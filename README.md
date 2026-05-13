# CLMD - DepEd Region XI

**Curriculum Learning Management Division** management system for the
Department of Education - Region XI.

## Stack
- PHP 7.4+ / CodeIgniter 3
- MySQL / MariaDB
- Bootstrap 5 + Bootstrap Icons

## Roles
| Role       | Capabilities |
|------------|--------------|
| Regional   | Manage divisions, users, curriculum; review (Approve / Revise / Reject) division-submitted learning materials. |
| Division   | Submit & manage own learning materials; view curriculum. |

## Modules
- **Dashboard** - role-aware statistics
- **Curriculum** - regional uploads (read-only for division)
- **Learning Materials** - division submissions, regional review workflow
- **Divisions** - regional-only CRUD
- **Users** - regional-only CRUD (assign role + division)
- **Activity Logs** - persisted on key actions

## Installation

1. Place the project at `htdocs/clmd/`.
2. Adjust DB credentials in:
   - `application/config/database.php`
   - `install.php`
3. Open in browser:
   ```
   http://localhost/clmd/install.php
   ```
   This creates the `clmd_db` database, imports `sql/clmd_db.sql`,
   and creates the default admin.
4. **Delete `install.php`** after install.
5. Visit `http://localhost/clmd/` and log in:
   - Username: `admin`
   - Password: `admin123` (change after first login)

## Default Divisions
The schema seeds the SDOs of Region XI (Davao Region):
Davao del Norte, del Sur, Oriental, Occidental, de Oro, Davao City,
IGACoS, Panabo, Tagum, Digos, Mati.

## Directory Layout
```
application/
  controllers/   Auth, Dashboard, Users, Divisions, Curriculum, Learning_materials
  models/        User_model, Division_model, Curriculum_model, Learning_material_model
  views/
    layouts/main.php       master layout (sidebar + topbar)
    auth/login.php         login page
    dashboard/, users/, divisions/, curriculum/, learning_materials/
  core/MY_Controller.php   auth + role guards, render helper, activity log
sql/clmd_db.sql            full schema with seed divisions
uploads/                   curriculum & materials uploads
```

## Notes
- File uploads are stored under `uploads/curriculum/` and `uploads/materials/`.
- Sessions use the default file driver (CI3).
- Passwords are hashed with `password_hash` (bcrypt).
