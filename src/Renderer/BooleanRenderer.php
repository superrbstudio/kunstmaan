<?php

namespace Superrb\KunstmaanAddonsBundle\Renderer;

class BooleanRenderer implements RendererInterface
{
    public function render(bool $value, string $true = 'Yes', string $false = 'No'): string
    {
        return '<span class="boolean boolean--'.($value ? 'true' : 'false').'">'.
            ($value ? $true : $false).'
        </span>';
    }
}
