<?php

namespace Superrb\KunstmaanAddonsBundle\Service;

class RecaptchaFlagService
{
    /**
     * @var bool
     */
    protected $recaptchaEnabled;

    public function __construct(bool $recaptchaEnabled)
    {
        $this->recaptchaEnabled = $recaptchaEnabled;
    }

    public function isRecaptchaEnabled(): bool
    {
        return $this->recaptchaEnabled;
    }
}
