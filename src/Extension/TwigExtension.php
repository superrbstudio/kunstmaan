<?php

namespace Superrb\KunstmaanAddonsBundle\Extension;

use DateTime;
use Money\Money;
use Superrb\KunstmaanAddonsBundle\Entity\Interfaces\LinkableEntityInterface;
use Superrb\KunstmaanAddonsBundle\Renderer\MoneyRenderer;
use Twig\TwigFilter;
use Twig\TwigTest;

class TwigExtension
{
    /**
     * @var MoneyRenderer
     */
    protected $moneyRenderer;

    public function __construct(
        MoneyRenderer $moneyRenderer
    ) {
        $this->moneyRenderer   = $moneyRenderer;
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
        ];
    }
}
