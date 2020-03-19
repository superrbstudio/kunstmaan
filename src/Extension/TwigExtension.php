<?php

namespace Superrb\KunstmaanAddonsBundle\Extension;

use DateTime;
use Money\Money;
use Superrb\KunstmaanAddonsBundle\Entity\Interfaces\LinkableEntityInterface;
use Superrb\KunstmaanAddonsBundle\Renderer\BooleanRenderer;
use Superrb\KunstmaanAddonsBundle\Renderer\MoneyRenderer;
use Twig\TwigFilter;
use Twig\TwigTest;

class TwigExtension
{
    /**
     * @var MoneyRenderer
     */
    protected $moneyRenderer;

    /**
     * @var BooleanRenderer
     */
    protected $booleanRenderer;

    public function __construct(
        MoneyRenderer $moneyRenderer,
        BooleanRenderer $booleanRenderer
    ) {
        $this->moneyRenderer   = $moneyRenderer;
        $this->booleanRenderer = $booleanRenderer;
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
        ];
    }
}
