<?php

namespace Superrb\KunstmaanAddonsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KumaSession
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity()
 */
class KumaSession
{
    /**
     * @ORM\Id
     * @ORM\Column(name="sess_id", type="string", length=128, nullable=false)
     */
    private $sessId;

    /**
     * @ORM\Column(name="sess_data", type="blob", nullable=false)
     */
    private $sessData;

    /**
     * @ORM\Column(name="sess_time", type="integer", nullable=false)
     */
    private $sessTime;

    /**
     * @ORM\Column(name="sess_lifetime", type="integer", nullable=false)
     */
    private $sessLifetime;
}
