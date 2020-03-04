<?php

namespace Superrb\KunstmaanAddonsBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TurbolinksLocationSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    const SESSION_KEY = 'turbolinks.redirect-location';

    /**
     * @var string
     */
    const HEADER_KEY = 'Turbolinks-Location';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var bool
     */
    private $enabled = false;

    /**
     * An array of Regexes used to filter out URLs.
     *
     * @var string[]
     */
    private $exclusionMap = [];

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => ['onKernelResponse'],
        ];
    }

    public function __construct(
        SessionInterface $session,
        bool $enabled = false,
        array $exclusionMap = []
    ) {
        $this->setSession($session);
        $this->setEnabled($enabled);
        $this->setExclusionMap($exclusionMap);
    }

    private function getSession(): SessionInterface
    {
        return $this->session;
    }

    private function setSession(SessionInterface $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    private function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExclusionMap(): array
    {
        return $this->exclusionMap;
    }

    /**
     * @param string[] $map
     */
    private function setExclusionMap(array $map): self
    {
        $this->exclusionMap = $map;

        return $this;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$this->isEnabled() || HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        /** @var Response $response */
        $response = $event->getResponse();
        $session  = $this->getSession();

        if ($response instanceof RedirectResponse) {
            $url = $this->filterUrl($response->getTargetUrl());

            if ($url) {
                $session->set(self::SESSION_KEY, $url);
            }
        } elseif ($url = $session->get(self::SESSION_KEY)) {
            $response->headers->set(self::HEADER_KEY, $url);
            $session->set(self::SESSION_KEY, null);
        }
    }

    protected function filterUrl(string $url): ?string
    {
        foreach ($this->getExclusionMap() as $regex) {
            if (preg_match($regex, $url, $matches)) {
                return null;
            }
        }

        return $url;
    }
}
