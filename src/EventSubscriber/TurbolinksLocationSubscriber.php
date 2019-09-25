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
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => ['onKernelResponse'],
        ];
    }

    /**
     * @param SessionInterface $session
     * @param bool             $enabled
     */
    public function __construct(SessionInterface $session, bool $enabled = false)
    {
        $this->setSession($session);
        $this->setEnabled($enabled);
    }

    /**
     * @return SessionInterface
     */
    private function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     *
     * @return self
     */
    private function setSession(SessionInterface $session): self
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return self
     */
    private function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$this->isEnabled() || HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        /** @var Response $response */
        $response = $event->getResponse();
        $session  = $this->getSession();

        if ($response instanceof RedirectResponse) {
            $session->set(self::SESSION_KEY, $response->getTargetUrl());
        } elseif ($url = $session->get(self::SESSION_KEY)) {
            $response->headers->set(self::HEADER_KEY, $url);
            $session->set(self::SESSION_KEY, null);
        }
    }
}
