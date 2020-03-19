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
        $imagick = $image->getImagick();

        if (method_exists($imagick, 'setImageAlpha')) {
            $imagick->setImageAlpha(0.0);
        } else {
            $imagick->setImageOpacity(0);
        }

        return $image;
    }
}
