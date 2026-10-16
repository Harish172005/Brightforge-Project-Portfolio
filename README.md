# Brightforge Digital — Project Portfolio

A WordPress-based software company portfolio website featuring a custom theme, child theme, and Project Management Toolkit plugin.

## Features

- **Custom theme:** Responsive layouts, navigation, blog sidebar, footer widgets, block patterns, and self-hosted fonts.
- **Child theme:** Supports customizations while preserving the parent theme.
- **Project management:** Custom post type with Industry and Technologies taxonomies.
- **Project details:** Project URL, status, completion date, and featured image.
- **Project listing:** `[projects]` shortcode with search, industry and technology filters, status filtering, and pagination.
- **Project analytics:** Custom database table for project views, transient caching, and a Most Viewed Projects widget.
- **Admin dashboard:** Configurable dashboard message with an enable/disable setting.
- **User permissions:** Project Manager role and custom project capabilities.
- **Upload restrictions:** Supports images and PDF files for permitted uploads.
- **Contact form:** WPForms with SMTP email integration.
- **SEO:** Yoast SEO integration and sitemap generation.
- **Performance:** W3 Total Cache and image lazy loading.
- **Backups:** UpdraftPlus integration.

## Tech Stack

| Area | Technologies |
|---|---|
| CMS | WordPress |
| Backend | PHP, WordPress APIs |
| Database | MySQL / MariaDB |
| Theme | PHP, HTML, CSS, JavaScript |
| Plugin architecture | Object-oriented PHP, Composer, PSR-4 autoloading |
| API | WordPress REST API, AJAX |
| Testing | PHPUnit |
| Local development | XAMPP, WP-CLI |

## Architecture

```text
Browser
   |
   v
Brightforge Child Theme
   |
   v
Brightforge Parent Theme
   |
   v
Project Management Toolkit
   |
   +-- Custom Post Type
   +-- Taxonomies and Metadata
   +-- Shortcode and Filters
   +-- REST API and AJAX
   +-- Project View Tracking
   +-- Roles and Capabilities
   |
   v
WordPress Core
   |
   v
MySQL / MariaDB
```

The plugin manages project data and application logic, while the theme handles presentation. This separation allows the theme to be changed without losing project content.

## Requirements

- XAMPP or an equivalent Apache, PHP, and MySQL environment
- WordPress 7.x (developed against WordPress 7.1.2)
- PHP 8.1+
- Composer
- WP-CLI (optional)

> Use a currently supported, security-patched WordPress release for deployment.

## Installation

1. Start Apache and MySQL in XAMPP.
2. Create a database using phpMyAdmin.
3. Install WordPress in `C:\xampp\htdocs\project-portfolio`.
4. Copy the theme, child theme, and plugin into the WordPress `wp-content/` directory.
5. Open the custom plugin directory and install its dependencies:

   ```bash
   composer install
   ```

6. In WordPress, activate **Brightforge Child** under **Appearance → Themes**.
7. Activate **Project Management Toolkit** under **Plugins**.
8. Go to **Settings → Permalinks**, select **Post name**, and save changes.

## Configuration

### Site settings

- Set the site title to **Brightforge Digital** under **Settings → General**.
- Configure the static Home page and Blog page under **Settings → Reading**.
- Assign the Primary and Footer navigation menus.
- Configure footer widgets and the blog sidebar.
- Set contact details in the theme customizer, if available.

### Plugin settings

Navigate to **Projects → Project Settings** to configure:

- Projects per page
- Default project status
- Dashboard message and its enable/disable setting

### User roles

- **Administrator and Editor:** Can manage projects, subject to configured capabilities.
- **Project Manager:** Can manage projects and upload permitted files.
- **Author and Contributor:** Cannot manage projects.

## Using the Plugin

### Add a project

1. Navigate to **Projects → Add New**.
2. Enter the project title, description, excerpt, and featured image.
3. Select the relevant industry and technologies.
4. Set the project URL, status, and completion date.
5. Publish the project.

### Display projects

Display the latest projects:

```text
[projects]
```

Display three projects:

```text
[projects limit="3"]
```

Enable search, filters, and pagination:

```text
[projects filters="yes"]
```

Filter by industry, technology, and status:

```text
[projects industry="fintech" technology="php" status="Completed"]
```

## REST API and AJAX

### REST API

```http
GET /wp-json/brightforge/v1/projects?page=1&per_page=6
```

Returns published project data in JSON format.

Example:

```bash
curl "http://localhost/project-portfolio/wp-json/brightforge/v1/projects?per_page=2"
```

### AJAX — Load More

The plugin provides an AJAX action for retrieving additional projects without a full page reload:

```text
brightforge_load_more_projects
```

Requests are sent to:

```text
/wp-admin/admin-ajax.php
```

## Security

The project incorporates:

- Input validation and sanitization
- Context-appropriate output escaping
- Nonce verification
- Capability checks for administrative actions
- Prepared SQL queries
- Restricted upload types
- Origin restrictions for cross-origin AJAX requests

Before publishing, verify these controls in the implementation. Never commit `wp-config.php`, credentials, private backups, or production user data.

## Performance and SEO

- Self-hosted fonts
- Image lazy loading
- W3 Total Cache integration
- One-hour transient caching for project-view statistics
- Yoast SEO integration and sitemap generation
- Optimized images and page caching

## Optional: WooCommerce

WooCommerce can be installed to add a branded-merchandise shop. Product setup and payment testing are optional.

## License

Licensed under the **GNU General Public License v2 or later (GPL-2.0-or-later)**.

**Author:** Harish Kumar M.

Fonts: Bricolage Grotesque and Public Sans, licensed under the SIL Open Font License. Third-party plugins and dependencies retain their respective licenses.
