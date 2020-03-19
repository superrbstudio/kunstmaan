<?php

namespace App\Form\Type;

use Money\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A read-only value type for forms - simply prints the value to screen with no input.
 */
class ReadOnlyValueType extends AbstractType implements DataMapperInterface
{
    /**
     * @var string
     */
    protected $name;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this->name = $builder->getName();
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'empty_data' => null,
            'required'   => false,
        ]);
    }

    /**
     * @param Iterator $forms
     * @param Money    $data
     */
    public function mapFormsToData($forms, &$data)
    {
        // Since this field is read only, we don't perform any mapping
    }

    /**
     * @param Money    $data
     * @param Iterator $forms
     */
    public function mapDataToForms($data, $forms)
    {
        // Since this field is read only, we don't perform any mapping
    }
}
