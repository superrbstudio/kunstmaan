# Kunstmaan Addons

A selection of addons for Kunstmaan Bundles

## Installation

### Step 1: Install the bundle

```
composer require superrb/kunstmaan-addons
```

Add `Superrb\KunstmaanAddonsBundle\SuperrbKunstmaanAddonsBundle::class => ['all' => true]`, to `config/bundles.php`

### Step 2: Add environment variables

If using turbolinks, add the following to `.env`

```dotenv
###> superrb/kunstmaan-addons ###
TURBOLINKS_ENABLED=true
###< superrb/kunstmaan-addons ###
```

To use the PDO session handler you need to convert your database URL to individual connection parameters.

Remove `DATABASE_URL` from `.env` and replace with:

```dotenv
DATABASE_PROTO=mysql
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306
DATABASE_USER=db_user
DATABASE_PASS=db_pass
DATABASE_NAME=db_name
DATABASE_EXTRA=?ssl-mode=REQUIRED
```

Change in `config/packages/doctrine.yaml`

```yaml
doctrine:
    dbal:
        url: "%env(resolve:DATABASE_URL)%"
```
to
```yaml
doctrine:
    dbal:
        url: "%database_proto%://%database_user%:%database_pass%@%database_host%:%database_port%/%database_name%%database_extra%"
```

and add the parameters to to the top of that file:

```yaml
parameters:
    database_proto: "%env(string:DATABASE_PROTO)%"
    database_host: "%env(resolve:DATABASE_HOST)%"
    database_port: "%env(int:DATABASE_PORT)%"
    database_user: "%env(string:DATABASE_USER)%"
    database_pass: "%env(string:DATABASE_PASS)%"
    database_name: "%env(string:DATABASE_NAME)%"
    database_extra: "%env(string:DATABASE_EXTRA)%"
```

## Configuration

## Step 1: Set up PDO Sessions

`config/packages/framework.yaml`

```yaml
framework:
    session:
        handler_id: kunstmaan_addons.session_handler
```

You will need to update your schema after

`bin/console doctrine:schem:update --force`

Or use migrations

## Added features

- Added version checking for the Kuma Admin area so that a user can see which bundles have been installed.
- Installs the `misd/phone-number-bundle` (https://packagist.org/packages/misd/phone-number-bundle) so phone number fields and validation can be used on any Kuma website.
- Adds a subscriber for Kernel Response events, that sets the correct headers to allow Turbolinks to follow redirects.
- Adds a PDO Session handler so sessions are stored in the database, handy for Load Balanced environments
- Adds a 'placeholder_image' filter for liip_imagine, to generate small fully transparent images used to reserve space in the layout when lazy loading images

## Issues and Troubleshooting
All issues: tech@superrb.com
