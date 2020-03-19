<?php

namespace Superrb\KunstmaanAddonsBundle\Form\Type;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class YesNoType extends ChoiceType implements DataMapperInterface
{
    /**
     * @var string[]
     */
    const OPTIONS = [
        'general.yes' => '1',
        'general.no'  => '0',
    ];

    /**
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Config
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->setDataMapper($this);
    }

    /**
     * @param array    $viewData
     * @param iterator $forms
     * @param mixed    $radios
     * @param mixed    $choice
     *
     * @return void
     */
    public function mapDataToForms($choice, $radios)
    {
        if (!\is_string($choice)) {
            throw new UnexpectedTypeException($choice, 'string');
        }

        $choice = true === $choice ? '1' : (false === $choice ? '0' : null);

        foreach ($radios as $radio) {
            $value = $radio->getConfig()->getOption('value');
            $radio->setData($choice === $value);
        }
    }

    /**
     * @param iterator $forms
     * @param array    $viewData
     * @param mixed    $radios
     * @param mixed    $choice
     *
     * @return void
     */
    public function mapFormsToData($radios, &$choice)
    {
        if (null !== $choice && !\is_string($choice)) {
            throw new UnexpectedTypeException($choice, 'null or string');
        }

        $choice = null;

        foreach ($radios as $radio) {
            if ($radio->getData()) {
                if ('placeholder' === $radio->getName()) {
                    return;
                }

                $choice = $radio->getConfig()->getOption('value');

                return;
            }
        }
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'choice_attr' => [],
            'choices'     => self::OPTIONS,
            'label'       => false,
            'expanded'    => true,
            'placeholder' => null,
            'mapped'      => false,
            'constraints' => [
                new Choice([
                    'choices' => self::OPTIONS,
                    'message' => 'yes_no.errors.invalid',
                ]),
            ],
        ]);
    }
}
