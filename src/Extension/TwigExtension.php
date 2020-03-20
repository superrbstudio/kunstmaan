<?php

namespace Superrb\KunstmaanAddonsBundle\Extension;

use DateTime;
use Money\Money;
use Superrb\KunstmaanAddonsBundle\Entity\Interfaces\LinkableEntityInterface;
use Superrb\KunstmaanAddonsBundle\Renderer\BooleanRenderer;
use Superrb\KunstmaanAddonsBundle\Renderer\CountryRenderer;
use Superrb\KunstmaanAddonsBundle\Renderer\LinkableEntityRenderer;
use Superrb\KunstmaanAddonsBundle\Renderer\MoneyRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

class TwigExtension extends AbstractExtension
{
    /**
     * @var MoneyRenderer
     */
    protected $moneyRenderer;

    /**
     * @var BooleanRenderer
     */
    protected $booleanRenderer;

    /**
     * @var CountryRenderer
     */
    protected $countryRenderer;

    /**
     * @var LinkableEntityRenderer
     */
    protected $linkableEntityRenderer;

    public function __construct(
        MoneyRenderer $moneyRenderer,
        BooleanRenderer $booleanRenderer,
        CountryRenderer $countryRenderer,
        LinkableEntityRenderer $linkableEntityRenderer
    ) {
        $this->moneyRenderer          = $moneyRenderer;
        $this->booleanRenderer        = $booleanRenderer;
        $this->countryRenderer        = $countryRenderer;
        $this->linkableEntityRenderer = $linkableEntityRenderer;
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return [
            new TwigTest(
                'money',
                function ($value) {
                    return $value instanceof Money;
                }
            ),
            new TwigTest(
                'date',
                function ($value) {
                    return $value instanceof DateTime;
                }
            ),
            new TwigTest(
                'linkable',
                function ($value) {
                    return $value instanceof LinkableEntityInterface;
                }
            ),
            new TwigTest(
                'boolean',
                function ($value) {
                    return is_bool($value);
                }
            ),
        ];
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter(
                'money',
                [$this->moneyRenderer, 'render']
            ),
            new TwigFilter(
                'bool',
                [$this->booleanRenderer, 'render'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'country',
                [$this->countryRenderer, 'render']
            ),
            new TwigFilter(
                'link',
                [$this->linkableEntityRenderer, 'render'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
