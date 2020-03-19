<?php

namespace Superrb\KunstmaanAddonsBundle\Renderer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Countries;

class CountryRenderer implements RendererInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function render(?string $countryCode): ?string
    {
        if (!$countryCode) {
            return null;
        }

        return Countries::getName($countryCode, $this->request->getLocale());
    }
}
