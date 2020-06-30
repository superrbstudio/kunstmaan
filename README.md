# Kunstmaan Addons

A selection of addons for Kunstmaan Bundles

## Installation

### Step 1: Install the bundle

```
composer require superrb/kunstmaan-addons
```

Add the following to `config/bundles.php`
```
Superrb\KunstmaanAddonsBundle\SuperrbKunstmaanAddonsBundle::class => ['all' => true],
Superrb\GoogleRecaptchaBundle\SuperrbGoogleRecaptchaBundle::class => ['all' => true] // If you plan to use Google Recaptcha
```

### Step 2: Add environment variables

If using turbolinks, add the following to `.env`

```dotenv
###> superrb/kunstmaan-addons ###
TURBOLINKS_ENABLED=true
###< superrb/kunstmaan-addons ###
```

## Added features

- Added version checking for the Kuma Admin area so that a user can see which bundles have been installed.
- Installs the `misd/phone-number-bundle` (https://packagist.org/packages/misd/phone-number-bundle) so phone number fields and validation can be used on any Kuma website.
- Adds a subscriber for Kernel Response events, that sets the correct headers to allow Turbolinks to follow redirects.
- Adds a 'placeholder_image' filter for liip_imagine, to generate small fully transparent images used to reserve space in the layout when lazy loading images

## Issues and Troubleshooting
All issues: tech@superrb.com
