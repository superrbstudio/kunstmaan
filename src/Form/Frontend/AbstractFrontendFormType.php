<?php

namespace Superrb\KunstmaanAddonsBundle\Form\Frontend;

use Superrb\GoogleRecaptchaBundle\Validator\Constraint\GoogleRecaptcha;
use Superrb\KunstmaanAddonsBundle\Service\RecaptchaFlagService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AbstractFrontendFormType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var bool
     */
    protected $recaptchaEnabled;

    public function __construct(UrlGeneratorInterface $urlGenerator, RecaptchaFlagService $recaptchaFlagService)
    {
        $this->urlGenerator     = $urlGenerator;
        $this->recaptchaEnabled = $recaptchaFlagService->isRecaptchaEnabled();
    }

    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if ($this->recaptchaEnabled && (!isset($options['recaptcha']) || true === $options['recaptcha'])) {
            $builder->add('recaptcha', HiddenType::class, [
                'mapped'      => false,
                'constraints' => [
                    new GoogleRecaptcha(),
                ],
                'help' => 'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank" rel="nofollow noopener">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank" rel="nofollow noopener">Terms of Service</a> apply.',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'recaptcha' => false,
            'attr'      => [
                'data-js-submit' => 'true',
            ],
        ]);
    }

    protected function jsonAttribute(array $data): string
    {
        return json_encode($data);
    }
}
