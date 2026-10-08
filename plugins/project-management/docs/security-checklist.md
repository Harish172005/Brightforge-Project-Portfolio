# Security checklist: Project Management Toolkit and Brightforge theme

Legend: `[x]` implemented in code. `[ ]` still to do or to check by hand.
Tick the "verify" lines yourself after running each test, and keep a screenshot as evidence.

Site used in the examples: `http://localhost/project-portfolio/`

---

## 1. Checklist (Module 8 requirements)

### Input: sanitize and validate
- [x] Project URL is cleaned with `esc_url_raw()` and limited to `http` and `https` (`ProjectMeta`)
- [x] Project status must be one of Planned, In Progress, Completed (allow-list, `ProjectMeta`)
- [x] Completion date must be a real `Y-m-d` date or empty (`ProjectMeta`)
- [x] Dashboard message is cleaned with `sanitize_text_field()`, the checkbox is forced to 0 or 1 (`AdminMessage`)
- [x] Projects per page is forced to a number from 1 to 50 (`SettingsPage`)
- [x] Default project status accepts only `publish` or `draft` (`SettingsPage`)
- [x] REST `page` and `per_page` are validated by schema: integers, `per_page` from 1 to 50 (`ProjectController`)
- [x] AJAX `page` is read with `absint()` and is never below 1 (`AjaxController`)
- [x] Theme Customizer fields use `sanitize_text_field` and `sanitize_email`

### Output: escape
- [x] Admin forms use `esc_attr()`, `esc_html()` and `selected()`
- [x] Theme templates use `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()` for term links
- [x] REST and AJAX send plain text only: tags stripped, no raw editor markup (`ProjectData`)
- [x] URLs in the JSON go through `esc_url_raw()`
- [ ] The React component must render values as text (no `dangerouslySetInnerHTML`)

### Nonces and CSRF
- [x] Project meta box: `wp_nonce_field()` and `wp_verify_nonce()` (`ProjectMeta`)
- [x] Settings form: `settings_fields()` adds the nonce, `options.php` verifies it
- [x] AJAX "Load more": `check_ajax_referer()` (`AjaxController`)
- [x] REST endpoint is GET and read-only, so it changes no data
- [ ] When the AJAX script is written, pass `getScriptData()` to it with `wp_localize_script()`

### Capabilities and roles
- [x] Meta box saving checks `edit_post` for that project
- [x] Settings, Java Projects page and dashboard message check `manage_options`
- [x] Projects use their own capabilities (`edit_projects`, `publish_projects`, ...) instead of the blog post ones
- [x] Administrator and Editor can manage projects. Authors and Contributors cannot
- [x] New role **Project Manager**: can manage projects and upload images, nothing else
- [x] Assigning Industry and Technology terms follows `edit_projects`
- [ ] Decide and write down who may do what (see section 3)

### Database
- [x] `$wpdb->prepare()` is used for the transient cleanup in `uninstall.php`
- [x] `$wpdb->prepare()` is used in `ProjectViews`
- [x] Custom table is dropped on uninstall
- [ ] When view tracking is added: use `$wpdb->insert()` with format arguments, validate the project ID, do not store a raw IP address (hash it)

### Direct access and cleanup
- [x] Every plugin file starts with the `ABSPATH` guard, `uninstall.php` checks `WP_UNINSTALL_PLUGIN`
- [x] Uninstall removes options, cached transients, the table, the role and the capabilities
- [x] Development CORS header for `localhost:5500` removed
- [x] Plugin no longer crashes when ACF is deactivated (`function_exists( 'get_field' )`)

### Uploads
- [x] The plugin has no upload field of its own
- [ ] Check that WordPress accepts images only for projects: try uploading a `.php` file in the media library, it must be refused
- [ ] Only trusted roles have `upload_files` (Administrator, Editor, Project Manager)

### Configuration and site
- [ ] Set `WP_DEBUG` to `false` for the submission and delete `wp-content/debug.log`
- [ ] `wp-config.php` and any `backup.wp-config.php` are not in the zip or the Git repository
- [ ] A database user other than `root`, with a password, for anything outside your own laptop
- [ ] Remove unused plugins and themes (Elementor, demo importers, unused themes)
- [x] `DISALLOW_FILE_EDIT` is set
- [ ] WordPress core, plugins and themes are updated (Dashboard, Updates)
- [ ] Run `composer audit` and remove the unused `monolog/monolog` package: `composer remove monolog/monolog`

### Backup
- [ ] UpdraftPlus is configured and one backup has been created (screenshot)
- [ ] Restore steps written in the README

---

## 2. Fixes made (before, after, file)

1. **REST route had no `permission_callback`.** Now `'permission_callback' => '__return_true'`, stated on purpose because the data is public and read-only. File: `src/Api/ProjectController.php`.
2. **REST parameters were not validated** (`?per_page=100000` loaded everything). Now `page` and `per_page` have type and limits, bad values return `400`, a page that does not exist returns `404`. Same file.
3. **REST and AJAX crashed without ACF.** `get_field()` is called only if it exists. File: `src/Support/ProjectData.php`.
4. **Raw editor content was returned as the description.** Now plain text from the excerpt, tags removed. Same file.
5. **REST response had no category.** Added `category`, plus `status`, and the `X-WP-Total` headers.
6. **AJAX had no nonce.** Now `check_ajax_referer()`; the script gets the nonce from `getScriptData()`. File: `src/Api/AjaxController.php`.
7. **CORS header was allowed on every request for `localhost:5500`.** Removed.
8. **Duplicate code in REST and AJAX.** Both use `ProjectData`, so a fix is made once.
9. **Settings accepted any number and any status.** Now clamped (1 to 50) and checked against a list. File: `src/Admin/SettingsPage.php`.
10. **URL field allowed any scheme.** Now `http` and `https` only. File: `src/Admin/ProjectMeta.php`.
11. **Projects used the blog post capabilities** (any Author could publish projects). Now own capabilities with `map_meta_cap`, and a Project Manager role. Files: `src/PostTypes/ProjectPostType.php`, `src/Support/ProjectRoles.php`, `src/Taxonomies/ProjectTaxonomy.php`.
12. **Uninstall left roles and capabilities behind.** Now removed. File: `uninstall.php`.
13. **`/projects/` returned 404 after activation.** The post type is now registered and the rewrite rules flushed on activation. File: `project-management.php`.

---

## 3. Who can do what

- Administrator: everything, including Project Settings and the dashboard message
- Editor: create, edit, publish and delete any project; assign Industry and Technology
- Project Manager: the same project rights, upload images, read the dashboard. No posts, pages, plugins or settings
- Author and Contributor: no access to projects
- Visitor (not logged in): sees published projects and the REST list, nothing else

Creating new Industry or Technology terms needs the `manage_categories` capability, so Project Manager can pick existing terms only.

---

## 4. How to verify (evidence for the demo)

Run these from a terminal (replace the address if yours differs).

1. REST works: `curl -i "http://localhost/project-portfolio/wp-json/brightforge/v1/projects"` returns `200` with `projects`, `total`, `pages`.
2. Too many per page is refused: add `?per_page=1000`, expect `400` and `rest_invalid_param`.
3. Bad type is refused: add `?page=abc`, expect `400`.
4. Missing page: add `?page=999`, expect `404` and `brightforge_page_out_of_range`.
5. AJAX without a nonce is refused: `curl -i -X POST -d "action=brightforge_load_more_projects&page=1" http://localhost/project-portfolio/wp-admin/admin-ajax.php`, expect `403`.
6. CORS is gone: `curl -i -H "Origin: http://localhost:5500" "http://localhost/project-portfolio/wp-json/brightforge/v1/projects"`, the response must not contain `Access-Control-Allow-Origin`.
7. ACF off: deactivate ACF, open the REST URL, it still returns `200` and `github_url` is empty.
8. Meta box: in the browser developer tools change the status `<option>` value to `Hacked` and save, the old status stays.
9. Settings: type `999` for projects per page, save, the field shows `50`.
10. Roles: Users, Add New, role "Project Manager". Log in as that user (private window): Projects menu is visible, Posts, Pages, Plugins and Settings are not.
11. Author test: log in as an Author, there must be no Projects menu.
12. After activation: open `/projects/` without re-saving permalinks, it must not show the 404 page.

Caching note: if W3 Total Cache serves cached pages, a cached page can hold an expired nonce. Test "Load more" with caching on and off.

---

## 5. Known remaining items

- View tracking does not write rows yet (`ProjectViews` creates the table only).
- Filtering by status and technology is not on the front end yet.
- The React component and the front-end AJAX script are not written yet.
- The configuration and backup items under section 1 need to be done by hand.
