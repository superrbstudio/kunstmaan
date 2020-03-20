<?php

namespace Superrb\KunstmaanAddonsBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use Twig\Environment;

class MailerService
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $templating;

    /**
     * @var string[]
     */
    protected $fromAddress;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $emailFrom;

    /**
     * @var string
     */
    protected $emailBcc;

    public function __construct(
        MailerInterface $mailer,
        Environment $templating,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        string $emailFrom,
        string $emailBcc
    ) {
        $this->mailer              = $mailer;
        $this->templating          = $templating;
        $this->translator          = $translator;
        $this->logger              = $logger;
        $this->emailFrom           = $emailFrom;
        $this->emailBcc            = $emailBcc;
    }

    public function send(string $template, string $email, array $options, ?string $siteId = null)
    {
        try {
            // Render the message content
            $body = $this->templating->render($template, $options);

            $mail = (new Email())
                ->subject($this->translator->trans($options['subject'], [], $this->siteId))
                ->from($this->formatAddress($options['from'] ?? $this->fromAddress))
                ->replyTo($this->formatAddress($options['reply_to'] ?? $this->fromAddress))
                ->to($this->formatAddress($email))
                ->addBcc($this->emailBcc)
                ->html($body);

            // Send the message
            $this->mailer->send($mail);

            return true;
        } catch (Throwable $e) {
            $this->logger->critical('Error "'.$e->getMessage().'" thrown whilst sending email');

            return false;
        }
    }

    /**
     * @param string|string[] $address
     */
    protected function formatAddress($address): Address
    {
        if (is_array($address)) {
            foreach ($address as $email => $name) {
                return new Address($email, $name);
            }
        }

        return new Address($address);
    }
}
