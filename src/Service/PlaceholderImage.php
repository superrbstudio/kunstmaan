<?php

namespace Superrb\KunstmaanAddonsBundle\Service;

use Imagine\Image\ImageInterface;
use Imagine\Imagick\Image;
use Liip\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;

class PlaceholderImage implements LoaderInterface
{
    /**
     * @param ImageInterface $image
     * @param array          $options
     *
     * @return ImageInterface
     */
    public function load(ImageInterface $image, array $options = [])
    {
        $image->getImagick()->setImageAlpha(0.0);

        return $image;
    }
}
