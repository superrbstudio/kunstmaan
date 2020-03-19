<?php

namespace Superrb\KunstmaanAddonsBundle\Renderer;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class MoneyRenderer implements RendererInterface
{
    public function render(Money $value, bool $includeDecimals = false): string
    {
        $numberFormatter = new NumberFormatter(null, NumberFormatter::CURRENCY);
        $numberFormatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, false);
        $formatter       = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());

        $value = $formatter->format($value);

        if (!$includeDecimals) {
            return str_replace('.00', null, $value);
        }

        return $value;
    }
}
