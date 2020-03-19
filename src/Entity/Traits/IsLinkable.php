<?php

namespace Superrb\KunstmaanAddonsBundle\Entity\Traits;

trait IsLinkable
{
    public function getLabel(): string
    {
        return $this->__toString();
    }

    public function getLabelSuffix(): ?string
    {
        return null;
    }

    public function getAdminLabel(): string
    {
        return $this->getId();
    }

    public function getAdminLabelSuffix(): ?string
    {
        return $this->getLabelSuffix();
    }
}
