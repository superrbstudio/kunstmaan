<?php

namespace App\Form\Type;

use Money\Currency;
use Money\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as MoneyFieldType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A form type for storing money values using the fowler money pattern.
 *
 * @see https://moneyphp.org
 */
class MoneyValueType extends AbstractType implements DataMapperInterface
{
    /**
     * @var string
     */
    const AMOUNT = 'amount';

    /**
     * @var string
     */
    const CURRENCY = 'currency';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::AMOUNT, MoneyFieldType::class, [
            'attr' => $options['amount_attr'] ?? [],
        ]);
        $builder->add(self::CURRENCY, CurrencyType::class, [
            'empty_data' => 'USD',
            'attr'       => array_merge($options['currency_attr'] ?? [], [
                'class' => 'js-advanced-select',
            ]),
        ]);
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => Money::class,
            'empty_data'    => null,
            'amount_attr'   => [],
            'currency_attr' => [],
        ]);
    }

    /**
     * @param Iterator $forms
     * @param Money    $data
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        $data  = new Money(
            bcmul($forms[self::AMOUNT]->getData(), '100'),
            new Currency($forms[self::CURRENCY]->getData())
        );
    }

    /**
     * @param Money    $data
     * @param Iterator $forms
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);

        if (!($data instanceof Money)) {
            $data = new Money(0, new Currency('USD'));
        }

        $forms[self::AMOUNT]->setData(bcdiv($data->getAmount(), '100'));
        $forms[self::CURRENCY]->setData($data->getCurrency()->getCode());
    }
}
