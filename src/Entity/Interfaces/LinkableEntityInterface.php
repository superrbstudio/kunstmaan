<?php

namespace Superrb\KunstmaanAddonsBundle\Entity\Interfaces;

interface LinkableEntityInterface
{
    /**
     * @return int|null
     */
    public function getId();

    public function getRoute(): string;

    public function getLabel(): string;

    public function getLabelSuffix(): ?string;

    public function getAdminRoute(): string;

    public function getAdminLabel(): string;

    public function getAdminLabelSuffix(): ?string;
}
