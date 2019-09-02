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
        $this->session = $session;
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        /** @var Response $response */
        $response = $event->getResponse();

        if ($response instanceof RedirectResponse) {
            $this->session->set(self::SESSION_KEY, $response->getTargetUrl());
        } elseif ($url = $this->session->get(self::SESSION_KEY)) {
            $response->headers->set(self::HEADER_KEY, $url);
            $this->session->set(self::SESSION_KEY, null);
        }
    }
}
