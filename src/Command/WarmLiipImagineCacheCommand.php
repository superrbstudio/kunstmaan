<?php

namespace Superrb\KunstmaanAddonsBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Kunstmaan\MediaBundle\Entity\Media;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class WarmLiipImagineCacheCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('kuma:media:warm-image-cache')
            ->setDescription('Create liip imagine image cache for all images')
            ->setHelp(
                'The <info>kuma:media:warm-image-cache</info> command can be used to warm the image cache for all images in the library.'
            );
    }

    /**
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating cached images... WARNING MAY TAKE A LONG TIME!');

        $contentTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        foreach ($contentTypes as $contentType) {
            $medias = $this->em->getRepository('KunstmaanMediaBundle:Media')->findBy(
                ['contentType' => $contentType, 'deleted' => false]
            );
            /** @var Media $media */
            foreach ($medias as $media) {
                $command = $this->getApplication()->find('liip:imagine:cache:resolve');
                $arguments = [
                    'paths' => [$media->getUrl()],
                ];
                try {
                    $greetInput = new ArrayInput($arguments);
                    $returnCode = $command->run($greetInput, $output);
                } catch (\Exception $e) {
                    $output->writeln('<comment>' . $e->getMessage() . '</comment>');
                }
            }
        }

        $output->writeln('<info>Image cache has been created.</info>');

        return 0;
    }
}
