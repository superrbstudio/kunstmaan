<?php

namespace Superrb\KunstmaanAddonsBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
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
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string|array
     */
    protected $emailFrom;

    /**
     * @var string|array
     */
    protected $emailBcc;

    public function __construct(
        MailerInterface $mailer,
        Environment $templating,
        TranslatorInterface $translator,
        LoggerInterface $logger,
        $emailFrom,
        $emailBcc
    ) {
        $this->mailer     = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->logger     = $logger;
        $this->emailFrom  = $emailFrom;
        $this->emailBcc   = $emailBcc;
    }

    /**
     * @param string|array $email
     */
    public function send(string $template, $email, array $options = []): bool
    {
        $mail = $this->createMessage($template, $email, $options);

        // Allow for using an alternative transport
        if (isset($options['transport'])) {
            $mail->getHeaders()->addTextHeader('X-Transport', $options['transport']);
        }

        // Send the message
        $this->mailer->send($mail);

        return true;
    }

    /**
     * @param string|array $email
     */
    public function createMessage(string $template, $email, array $options = []): Email
    {
        // Render the message content
        $body = $this->templating->render($template, $options);

        $mail = (new Email())
            ->subject($this->translator->trans($options['subject']))
            ->from(...$this->formatAddress($options['from'] ?? $this->emailFrom))
            ->replyTo(...$this->formatAddress($options['reply_to'] ?? $this->emailFrom))
            ->to(...$this->formatAddress($email))
            ->addBcc($this->emailBcc)
            ->html($body);

        return $mail;
    }

    /**
     * @param string|string[] $address
     */
    protected function formatAddress($address): array
    {
        if (is_array($address)) {
            return array_map(function (string $email, string $name) {
                return new Address($email, $name);
            }, array_keys($address), array_values($address));
        }

        return [new Address($address)];
    }
}
