<?php

namespace Superrb\KunstmaanAddonsBundle\Form\Type;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType as MisdPhoneNumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A simple form type which extends the phone number type field, and replaces
 * the list of countries with their ISO code for brevity.
 */
class PhoneNumberType extends MisdPhoneNumberType
{
    /**
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $preferredChoices = $view->children['country']->vars['preferred_choices'];
        $choices          = $view->children['country']->vars['choices'];

        foreach ([$preferredChoices, $choices] as $items) {
            foreach ($items as $item) {
                $item->label = preg_replace_callback('/(?:.*)(\(\+\d+\))/', function ($matches) use ($item) {
                    return $item->value.' '.$matches[1];
                }, $item->label);
            }
        }
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'widget'                    => MisdPhoneNumberType::WIDGET_COUNTRY_CHOICE,
            'preferred_country_choices' => ['GB'],
            'default_region'            => 'GB',
        ]);
    }
}
