# CLMD Document Submission & Approval System

Curriculum and Learning Management Division — DepEd Region XI.

A document submission, approval, and certification workflow management system.
**Not a Learning Management System (LMS).**

## Roles
| Role     | Capabilities |
|----------|--------------|
| Division | Manage own school list (Public/Private), upload documents tied to a school, edit/resubmit on revision. |
| Regional | Receive notifications of new submissions, review (Approve / Reject / Return for Revision), manage Users, Divisions, system Settings (CLMD Chief signature). |

## Workflow
```
Division → adds school → uploads document (status: For Approval)
        → notifies Regional users
Regional → reviews → Approves (auto-assigns Control No.)
        → notifies Division submitter
Division → opens approved document → Print Certification
        → certificate has e-signature + QR code (links to public verify page)
```

## Modules
- **Schools** - Division CRUD (Name, Code, Public/Private, Address, Status)
- **Documents** - Division upload + Regional review/approval
- **Notifications** - Bell icon in topbar, dropdown + full list page
- **Print Certification** - A4 printable, with letterhead, e-signature, QR code
- **Public Verify** - `/verify/{control_no}` (no login required, used by QR)
- **Settings** - Regional only; Chief name/position, e-signature image
- **Users / Divisions** - Regional only

## Stack
- PHP 7.4+, CodeIgniter 3
- MySQL/MariaDB
- Bootstrap 5 + Bootstrap Icons
- QR codes via `api.qrserver.com` (no extra deps)

## Install
1. Place at `htdocs/clmd/`
2. Adjust DB credentials in `application/config/database.php` and `install.php`
3. Open `http://localhost/clmd/install.php`
4. **Delete `install.php`**
5. Visit `http://localhost/clmd/`
   - Login: `admin` / `admin123`
6. Configure CLMD Chief & e-signature in **Settings**
7. Create a Division user (regional only) and have them add schools and submit documents

## Public Routes
- `/login`
- `/verify/{control_no}` — public certificate verification (QR target)

## Authenticated Routes
- `/dashboard`
- `/schools`, `/schools/create`, `/schools/edit/{id}`
- `/documents`, `/documents/create`, `/documents/view/{id}`, `/documents/review/{id}`, `/documents/certificate/{id}`
- `/notifications`
- `/users`, `/divisions`, `/settings` (regional only)

## File Uploads
- `uploads/documents/` — submitted document files
- `uploads/signature/` — CLMD Chief e-signature
