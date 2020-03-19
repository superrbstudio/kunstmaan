<?php

namespace Superrb\KunstmaanAddonsBundle\Renderer;

use Superrb\KunstmaanAddonsBundle\Entity\Interfaces\LinkableEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LinkableEntityRenderer
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator
    ) {
        $this->request      = $requestStack->getCurrentRequest();
        $this->urlGenerator = $urlGenerator;
        $this->translator   = $translator;
    }

    public function render(LinkableEntityInterface $entity): string
    {
        $route  = $entity->getRoute();
        $label  = $entity->getLabel();
        $suffix = $entity->getLabelSuffix();

        if ($this->isAdmin()) {
            $route  = $entity->getAdminRoute();
            $label  = $entity->getAdminLabel();
            $suffix = $entity->getAdminLabelSuffix();
        }

        $url    = $this->urlGenerator->generate($route, ['id' => $entity->getId()]);
        $label  = nl2br($this->translator->trans($label));
        $suffix = nl2br($this->translator->trans($suffix));

        return '<a href="'.$url.'">'.$label.'</a>'.($suffix ? ' '.$suffix : null);
    }

    protected function isAdmin(): bool
    {
        return preg_match('{^/admin}', $this->request->getRequestUri());
    }
}
