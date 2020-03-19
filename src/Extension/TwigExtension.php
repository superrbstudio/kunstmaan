<?php

namespace Superrb\KunstmaanAddonsBundle\Extension;

use Money\Money;
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
