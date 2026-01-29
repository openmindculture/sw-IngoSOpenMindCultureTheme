# Ingo's Open Mind Culture Theme for Shopware 6 

## sw-IngoSOpenMindCultureTheme

A Shopware 6 theme used for my Open Mind Culture shop to match the existing theme of the [Open Mind Culture blog](https://www.open-mind-culture.org) to experiment and showcase frontend customization possibilities while maintaining performance, sustainability, SEO and accessibility.

Theme style and layout are inspired by the Open Mind Culture WordPress blog and its modified fasto theme. This is a work in progress. This theme project might serve as a blueprint for future Shopware 6 themes. No warranty. License: proprietary.

The development enviroment is hased on [Ingo's Masonry Theme](https://github.com/openmindculture/sw-IngoSMasonryTheme) and [Ingo's Cost Transparency](https://github.com/openmindculture/sw-IngoSCostTransparency) extension, based on my [Shopware 6 Theme/Plugin Development Template](https://github.com/openmindculture/IngoSDev6CertPrep), using the Dockware docker-compose setup. Note that the older theme projects might be deprecated.

### Theme Code vs CMS Layout

Modern Shopware 6 (6.4+) favors no-code Shopping Experiences with dynamic product listing/slider blocks sourcing featured products from rule-based groups—fully configurable in admin without touching storefront/component files. Shopware 6 CMS records (Shopping Experiences layouts, blocks, pages) persist in the database and must be exported/imported separately from theme files during deployment.

See the section about [Backup/Export/Deployment](#backup-export-deployment) below.

Layouts, products and media (images) for the Open Mind Culture shop are stored in the `/data` directory of this repository. Installable theme release files are in `/dist`.

## Development

Install and activate the theme in the localhost storefront as a live preview. Some changes, like modified content of existing twig template files, are effective immediately after browser reload.

Changes in compiled asset sources require recompilation:

`bin/console cache:clear && bin/console theme:compile`

before reloading the storefont in the browser.

### Setup Dockware Development Environment

The localhost setup is based on the lastest  [dockware](https://docs.dockware.io/)  dev image. We don't need no parent project container repository anymore! `custom/plugins` is now mounted to the project `src` directory as recommended in the official examples.

### Start the Shopware Development Container

- `docker compose up -d`

### Open the Storefront or Administration in a Browser

- http://localhost/
- http://localhost/admin (default credentials: admin:shopware)

### Enter the Container Shell

- `docker exec -it theme bash`

You will start in the Shopware project root `/var/www/html` where you can type console commands like
`bin/console plugin:create foobar`
to create a new plugin structure.

When reusing existing code, you might need to grant access permissions explicitly:
- `sudo chmod ugo+rwx /var/www/html/custom/plugins/IngoSOpenMindCultureTheme/src/Resources/app/storefront/dist/`

- use IngoSOpenMindCultureTheme in the storefront and for development
  - `bin/console plugin:refresh`
  - `bin/console plugin:install --activate IngoSOpenMindCultureTheme`
  - `bin/console cache:clear`
  - `bin/console bundle:dump`
  - `bin/build-storefront.sh`
  - `bin/console theme:refresh`
  - `bin/console theme:compile`
  - `bin/console theme:change`

#### applying changes

For SCSS changes to take effect, running:
- `bin/console theme:compile && bin/console cache:clear` is sufficient. Twig template changes should take effect immediately after browser reload.

#### Optional Verbose vs. Silent Switches

There is no verbose switch.
Scripts seem to output verbose warnings by default. Add `--no-debug` to suppress  noncritical warnings and deprecation messages, e.g.:

- `bin/console theme:compile --no-debug`

### Stop the Container

- `docker-compose stop`

### Remove the Container

- `docker-compose down -v` (-v will remove created volumes)

## Logfile Locations

### Shopware Logs in Dockware

- `/var/www/html/var/log`

Too see the latest: `tail /var/www/html/var/log/dev.log`

#### System Logs in Dockware

- `/var/log`

### Shopware Platform Source Code in Dockware

- `/var/www/html/vendor/shopware`

- TODO: mounting this as a secondary volume broke the installation.

- Workaround to see the shop source in the IDE: check it out into another, git-ignored, directory:

- `git clone https://github.com/shopware/shopware.git sw_platform_src`

- then "mark directory as" -> "sources root"

### Extension Export and Verification

Last but not least, you can build an exportable zip archive file to upload into a shop backend or Shopware's plugin marketplace.

There is an optional Shopware CLI that is not included in Dockware. You can get it from
[sw-cli.fos.gg](https://sw-cli.fos.gg) and use the `extension` command to build a theme archive using all files:

- `shopware-cli extension zip ./custom/plugins/IngoSOpenMindCultureTheme --disable-git --output-directory .`

But this will include the `vendor` directory and produce a huge file, so we must either use a git (release) branch,
or create a zip manually.

Expected structure inside the zip file `IngoSOpenMindCultureTheme.zip`:
`IngoSOpenMindCultureTheme/`
`IngoSOpenMindCultureTheme/src`
`IngoSOpenMindCultureTheme/composer.json`

So we can zip our src/IngoSOpenMindCultureTheme directory.

We can still use `sw-cli` to validate our extension archive:

- `shopware-cli extension validate ./custom/plugins/IngoSOpenMindCultureTheme.zip`

<a name="backup-export-deployment" id="backup-export-deployment"></a>
### Backup/Export/Deployment

#### CMS JSON Export

Run these commands on the preview server to export CMS data as JSON files:

`./bin/console dal:export:layout --all > layouts.json` (layouts/shopping experiences)

`./bin/console cms:export --directory=var/export/cms/` (full CMS structure including pages/sections)

These files are placed alongside theme assets in the `/data` directory in this deployment repository .

### Plugin Data and Settings Export/Import

Custom fields added to products export automatically in standard product CSV, but plugin-specific data like settings from extensions (e.g., [Cost Transparency](https://store.shopware.com/en/ingos57544164693f/cost-transparency.html) adding supply chain price breakdown fields to products) is stored in the database as custom field sets, entity extensions, or plugin config tables. Export them using `./bin/console dal:export:entity --entity app.config --all > plugin-configs.json` on the source server. Import with `./bin/console dal:import:entity plugin-configs.json` on the target server.

#### Product Catalog Export/Import

- Settings → Import/Export → Products profile → Download CSV
- Rsync/SCP `/public/media` folder to live server
- Upload CSV → Match columns → Start import

#### Production Deployment and Data Import 

Deploy the theme using `bin/console` or the admin theme manager.

Import CMS: `./bin/console dal:import:layout layouts.json` or `./bin/console cms:import var/export/cms/`

#### AI assistance

A helpful global system prompt for generative AI assistance might be:

Prefer correct, complete, but short first answers! Omit unlikely edge cases! Never give misleading advice! Prefer best practices that are valid for the latest stable software and language versions unless explicitly requested otherwise. Always check your answers and add links to authoritative sources that prove why your answer is correct. 