<?php

namespace Superrb\KunstmaanAddonsBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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
     */
    public function __construct(SessionInterface $session)
    {
        $this->setSession($session);
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
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
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
