# Kunstmaan Addons
A selection of addons for Kunstmaan Bundles
## Installation
`composer require superrb/kunstmaan-addons`

If using turbolinks, add the following to `.env`
```
TURBOLINKS_ENABLED=true
```
## Added features
- Added version checking for the Kuma Admin area so that a user can see which bundles have been installed.
- Installs the `misd/phone-number-bundle` (https://packagist.org/packages/misd/phone-number-bundle) so phone number fields and validation can be used on any Kuma website.
- Adds a subscriber for Kernel Response events, that sets the correct headers to allow Turbolinks to follow redirects.
## Issues and Troubleshooting
All issues: tech@superrb.com
